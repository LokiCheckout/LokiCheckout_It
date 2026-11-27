# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.1] - 27 November 2026
### Added
- Italian address field labels, placeholders and input masks for the shipping and billing address
- Italian VAT field accepts the Codice Fiscale and only the `IT` country prefix
- `+39` telephone placeholder for Italian addresses
- Italian translations (`it_IT`) for the labels introduced by this module
- Support for PHP 8.5

### Changed
- Address settings now use the new address-specific and country-specific layout handles
- VAT ID mask is applied only to the `vat_id` field
- Requires LokiCheckout_Core 2.7.4 and Loki_Components 2.9

### Removed
- Support for PHP 8.1

### Fixed
- Italian street field widths now apply to all skins, not only the default skin
