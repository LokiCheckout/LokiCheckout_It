---
status: complete
---

# Italian address grid

**As a** customer with an Italian address,  
**I want to** see the house number and addition next to each other,  
**So that** the address form is short and easy to fill in.

## Acceptance Criteria

### when-country-is-italy

**Given** the shipping or billing country is "Italy"  
**When** the customer views the address form with any checkout skin  
**Then** the house number and addition fields each take half a row on mobile and a quarter row on desktop  
**And** the country field takes a full row  
**And** the `italy` grid of the `default_grid` layout is used.

## Tests
- `Test/Integration/Config/XmlConfig/Value/GridLayoutTest.php`
- `Test/Integration/Config/XmlConfig/Value/GridLayout/GridTest.php`
- `Test/Integration/Layout/ItalianAddressFieldsTest.php`
