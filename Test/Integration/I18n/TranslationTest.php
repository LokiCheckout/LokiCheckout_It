<?php
declare(strict_types=1);

namespace LokiCheckout\It\Test\Integration\I18n;

use Magento\Framework\App\Area;
use Magento\Framework\TranslateInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class TranslationTest extends TestCase
{
    #[DataProvider('getTranslations')]
    public function testItalianTranslation(string $text, string $expectedTranslation): void
    {
        $translate = Bootstrap::getObjectManager()->create(TranslateInterface::class);
        $translate->setLocale('it_IT');
        $translate->loadData(Area::AREA_FRONTEND, true);

        $translations = $translate->getData();
        $this->assertArrayHasKey($text, $translations, 'No Italian translation for "'.$text.'"');
        $this->assertSame($expectedTranslation, $translations[$text]);
    }

    public static function getTranslations(): array
    {
        return [
            ['Street Name', 'Indirizzo'],
            ['Housenumber', 'Civico'],
            ['Addition', 'Interno'],
            ['Postal code', 'CAP'],
            ['Province', 'Provincia'],
            ['VAT number or tax code', 'Partita IVA o Codice Fiscale'],
        ];
    }
}
