---
status: complete
---

# Italian VAT number or tax code

**As a** customer with an Italian address,  
**I want to** enter either my Partita IVA or my Codice Fiscale in the VAT field,  
**So that** my invoice contains the identification number I am required to use.

## Acceptance Criteria

### when-country-is-italy

**Given** the setting "Show VAT Number" is enabled  
**And** the shipping or billing country is "Italy"  
**When** the customer views the address form  
**Then** the VAT field is labelled "VAT number or tax code" with the placeholder "IT12345678901".

### when-entering-a-partita-iva

**Given** the country is "Italy"  
**When** the customer types digits in the VAT field  
**Then** at most 11 digits are accepted.

### when-entering-a-partita-iva-with-prefix

**Given** the country is "Italy"  
**When** the customer types "IT" followed by digits  
**Then** "IT" followed by at most 11 digits is accepted.

### when-entering-a-codice-fiscale

**Given** the country is "Italy"  
**When** the customer types a Codice Fiscale like "RSSMRA85T10A562S"  
**Then** the full 16-character code is accepted.

### when-entering-a-foreign-vat-number

**Given** the country is "Italy"  
**When** the customer types a foreign VAT number like "DE123456789"  
**Then** only the letters "DE" are kept.

## Tests
- `Test/Integration/Layout/ItalianAddressFieldsTest.php`
- `Test/Playwright/country-switch-test.spec.ts`
- `Test/Playwright/input-mask-test.spec.ts`
