---
status: complete
---

# Italian postcode mask

**As a** customer with an Italian address,  
**I want to** be stopped from entering an invalid CAP,  
**So that** my order is not shipped to a wrong address.

## Acceptance Criteria

### when-typing-a-postcode

**Given** the shipping or billing country is "Italy"  
**When** the customer types "00184AB12" in the postcode field  
**Then** the field only contains "00184".

## Tests
- `Test/Integration/Layout/ItalianAddressFieldsTest.php`
- `Test/Playwright/input-mask-test.spec.ts`
