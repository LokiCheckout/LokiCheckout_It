---
status: complete
---

# Italian translations

**As a** customer of an Italian store view,  
**I want to** see the address labels in Italian,  
**So that** I understand the checkout in my own language.

## Acceptance Criteria

### when-locale-is-it-it

**Given** the store view uses the locale `it_IT`  
**When** the customer views the address form  
**Then** the labels show "Indirizzo", "Civico", "Interno", "CAP", "Provincia" and "Partita IVA o Codice Fiscale".

## Tests
- `Test/Integration/I18n/TranslationTest.php`
