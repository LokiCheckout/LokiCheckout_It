import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

const labelText = (text: string): RegExp => {
  const escapedText = text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  return new RegExp('^\\s*' + escapedText + '\\s*\\*?\\s*$');
};

test.describe('country-switch', function () {
  test('should show Italian labels and placeholders only for Italy', async function ({page, context}) {
    await setupCheckout(page, context, merge(coreConfig, {
      config: {
        'loki_checkout/fields/hide_region_when_optional': 0,
        'customer/address/taxvat_show': 'opt',
        'customer/address/telephone_show': 'req',
      }
    }));

    const countryField = new Field(page, 'shipping.country_id');
    const postcodeField = new Field(page, 'shipping.postcode');
    const regionField = new Field(page, 'shipping.region');
    const telephoneField = new Field(page, 'shipping.telephone');
    const vatIdField = new Field(page, 'shipping.vat_id');

    async function switchCountry(country: string) {
      await Promise.all([
        page.waitForResponse(response => response.status() === 200),
        countryField.fill(country),
      ]);
    }

    async function expectPlaceholder(field: Field, placeholder: string | false) {
      if (placeholder) {
        await expect(field.getLocator()).toHaveAttribute('placeholder', placeholder);
        return;
      }

      await expect(field.getLocator()).not.toHaveAttribute('placeholder', /.+/);
    }

    for (let round = 0; round < 2; round++) {
      await switchCountry('IT');
      await expect(postcodeField.getLabelLocator()).toHaveText(labelText('Postal code'));
      await expect(regionField.getLabelLocator()).toHaveText(labelText('Province'));
      await expect(vatIdField.getLabelLocator()).toHaveText(labelText('VAT number or tax code'));
      await expectPlaceholder(postcodeField, '00184');
      await expectPlaceholder(telephoneField, '+39 312 345 6789');
      await expectPlaceholder(vatIdField, 'IT12345678901');

      await switchCountry('US');
      await expect(postcodeField.getLabelLocator()).toHaveText(labelText('Zip/Postal Code'));
      await expect(regionField.getLabelLocator()).toHaveText(labelText('State/Province'));
      await expect(vatIdField.getLabelLocator()).not.toHaveText(labelText('VAT number or tax code'));
      await expectPlaceholder(telephoneField, false);
      await expectPlaceholder(vatIdField, false);
    }
  });
});
