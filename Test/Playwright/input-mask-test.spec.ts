import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('input-masks', function () {
  test.beforeEach(async function ({page, context}) {
    await setupCheckout(page, context, merge(coreConfig, {
      config: {
        'customer/address/taxvat_show': 'opt',
      }
    }));

    const countryField = new Field(page, 'shipping.country_id');
    await Promise.all([
      page.waitForResponse(response => response.status() === 200),
      countryField.fill('IT'),
    ]);
  });

  test('should allow only 5 digits in the postcode', async function ({page}) {
    const postcodeField = new Field(page, 'shipping.postcode');
    await postcodeField.getLocator().clear();
    await postcodeField.getLocator().pressSequentially('00184AB12');
    await expect(postcodeField.getLocator()).toHaveValue('00184');
  });

  const vatExpectations = [
    {name: 'a Partita IVA', input: '1234567890123', expected: '12345678901'},
    {name: 'a Partita IVA with IT prefix', input: 'IT1234567890123', expected: 'IT12345678901'},
    {name: 'a Codice Fiscale', input: 'RSSMRA85T10A562S', expected: 'RSSMRA85T10A562S'},
    {name: 'a foreign VAT prefix', input: 'DE123456789', expected: 'DE'},
  ];

  for (const vatExpectation of vatExpectations) {
    test('should mask ' + vatExpectation.name + ' in the VAT field', async function ({page}) {
      const vatIdField = new Field(page, 'shipping.vat_id');
      await vatIdField.getLocator().clear();
      await vatIdField.getLocator().pressSequentially(vatExpectation.input);
      await expect(vatIdField.getLocator()).toHaveValue(vatExpectation.expected);
    });
  }
});
