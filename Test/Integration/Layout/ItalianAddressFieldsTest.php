<?php
declare(strict_types=1);

namespace LokiCheckout\It\Test\Integration\Layout;

use Loki\Components\Util\Controller\LayoutLoader;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
class ItalianAddressFieldsTest extends TestCase
{
    #[ConfigFixture('customer/address/taxvat_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/telephone_show', 'opt', 'store', 'default')]
    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testItalianFieldsWithDefaultSkin(): void
    {
        $layout = $this->getLayout('IT', 'IT');
        $this->assertItalianFields($layout, 'shipping');
        $this->assertItalianFields($layout, 'billing');
    }

    #[ConfigFixture('customer/address/taxvat_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/telephone_show', 'opt', 'store', 'default')]
    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    public function testItalianFieldsWithOnestepSkin(): void
    {
        $layout = $this->getLayout('IT', 'IT');
        $this->assertItalianFields($layout, 'shipping');
        $this->assertItalianFields($layout, 'billing');
    }

    #[ConfigFixture('customer/address/taxvat_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/telephone_show', 'opt', 'store', 'default')]
    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    public function testMixedCountriesWithItalianBillingAddress(): void
    {
        $layout = $this->getLayout('DE', 'IT');
        $this->assertItalianFields($layout, 'billing');

        $this->assertNotEquals('00184', (string)$this->getBlock($layout, 'shipping', 'postcode')->getPlaceholder());
        $this->assertNotEquals(3, (int)$this->getBlock($layout, 'shipping', 'street1')->getMdColSpan());

        $config = Bootstrap::getObjectManager()->get(Config::class);
        $billingForm = $layout->getBlock('loki-checkout.billing.address.form');
        $shippingForm = $layout->getBlock('loki-checkout.shipping.address.form');
        $this->assertEquals('italy', $config->getGrid($billingForm)->getName());
        $this->assertNotEquals('italy', $config->getGrid($shippingForm)->getName());
    }

    private function assertItalianFields(LayoutInterface $layout, string $addressType): void
    {
        $expectedLabels = [
            'street0' => 'Street Name',
            'street1' => 'Housenumber',
            'street2' => 'Addition',
            'postcode' => 'Postal code',
            'region' => 'Province',
            'vat_id' => 'VAT number or tax code',
        ];

        foreach ($expectedLabels as $field => $expectedLabel) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertEquals($expectedLabel, (string)$block->getFieldLabel(), $block->getNameInLayout());
        }

        foreach (['street1', 'street2'] as $field) {
            $block = $this->getBlock($layout, $addressType, $field);
            $this->assertEquals(6, (int)$block->getColSpan(), $block->getNameInLayout().' col_span');
            $this->assertEquals(3, (int)$block->getMdColSpan(), $block->getNameInLayout().' md_col_span');
        }

        $postcode = $this->getBlock($layout, $addressType, 'postcode');
        $this->assertEquals('00184', $postcode->getPlaceholder());
        $this->assertEquals('99999', $postcode->getFieldAttributes()['x-mask'] ?? null);

        $telephone = $this->getBlock($layout, $addressType, 'telephone');
        $this->assertEquals('+39 312 345 6789', $telephone->getPlaceholder());

        $vatId = $this->getBlock($layout, $addressType, 'vat_id');
        $this->assertEquals('IT12345678901', $vatId->getPlaceholder());
        $this->assertEquals('itVatIdMask', $vatId->getFieldAttributes()['x-mask:dynamic'] ?? null);
    }

    private function getBlock(LayoutInterface $layout, string $addressType, string $field): AbstractBlock
    {
        $blockName = 'loki-checkout.'.$addressType.'.address.'.$field;
        $block = $layout->getBlock($blockName);
        $this->assertInstanceOf(AbstractBlock::class, $block, $blockName);

        return $block;
    }

    private function getLayout(string $shippingCountryId, string $billingCountryId): LayoutInterface
    {
        $objectManager = Bootstrap::getObjectManager();
        $cart = $objectManager->get(DataFixtureStorageManager::class)->getStorage()->get('cart');
        $cart = $objectManager->get(CartRepositoryInterface::class)->get((int)$cart->getId());
        $cart->getShippingAddress()->setCountryId($shippingCountryId);
        $cart->getBillingAddress()->setCountryId($billingCountryId);

        $checkoutState = $objectManager->get(CheckoutState::class);
        $checkoutState->setQuote($cart);
        $checkoutState->setQuoteId((int)$cart->getId());
        $objectManager->get(CheckoutSession::class)->replaceQuote($cart);

        return $objectManager->get(LayoutLoader::class)->load(['checkout_index_index'], [], true);
    }
}
