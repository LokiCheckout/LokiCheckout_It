---
status: complete
---

# Italian address labels and placeholders

**As a** customer with an Italian address,  
**I want to** see address fields labelled the way Italian addresses are written,  
**So that** I know which part of my address goes into which field.

## Acceptance Criteria

### when-country-is-italy

**Given** the shipping or billing country is "Italy"  
**When** the customer views the address form  
**Then** the street fields are labelled "Street Name", "Housenumber" and "Addition"  
**And** the postcode field is labelled "Postal code" with the placeholder "00184"  
**And** the region field is labelled "Province"  
**And** the telephone field has the placeholder "+39 312 345 6789".

### when-switching-away-from-italy

**Given** the customer has selected "Italy" as country  
**When** the customer switches to another country  
**Then** the default labels and placeholders of that country are shown again.

### when-only-billing-address-is-italian

**Given** the shipping country is not "Italy"  
**And** the billing country is "Italy"  
**When** the customer views the checkout  
**Then** only the billing address shows the Italian labels and layout.

## Tests
- `Test/Integration/Layout/ItalianAddressFieldsTest.php`
- `Test/Playwright/country-switch-test.spec.ts`
