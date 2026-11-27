# Changelog

## 2.0.0

### Breaking changes

- Requires Magento 2.4.6 or newer and PHP 8.1 or newer. Older versions of Magento and PHP 7.1 to 8.0 are no longer
  supported; use version 1.x for these.
- Requires `infrangible/m2-catalog-product-option-composite` 6.0, `infrangible/m2-catalog-product-option-product` 2.0
  and `infrangible/m2-foundation` 3.0. Version 1.x allowed Catalog Product Option Composite up to 5.0 and Catalog
  Product Option Product 1.19 only; with the current versions of both, its block cannot be loaded (PHP fatal error:
  the return types of `getProduct()` of the two parents differ).
- `Block\Quote\Item\Bundle\Products\Selection\Options\Type\Product` extends `DefaultType` of Catalog Product Option
  Composite instead of the option block of Catalog Product Option Product. It renders the option with the block of the
  product page, which `getOptionRenderer()` returns with the product of the bundle option as main product. The block
  returns the product, price and selected value of the option (`getOptionProduct()`, `getOptionPrice()`,
  `isSelected()`, `getSelectedValue()`, `isRequired()`); the template takes the swatches from the renderer.

### Fixed

- A simple product of an option that was in the cart item was not selected on the page for changing the bundle item: its
  checkbox was unchecked and its field was "0", because the template looked for the option ID in a list instead of
  reading the stored product ID. Saving the page removed the product from the cart item, and clicking the checkbox to
  remove it added it again. Now the checkbox is checked and the field contains the product, like for configurable
  products.

### Changed

- The checkbox of an option sets its value with the JavaScript component of Catalog Product Option Product instead of
  a script in the template.
- The fields of the options have IDs with the ID of the cart item, like the other options of Catalog Product Option
  Composite; the checkbox of a configurable product keeps its ID, which the swatches use.
- The module is loaded after the modules it uses.
- Checks with PHPStan level 9, the Magento coding standard and unit tests on every push.

### Added

- Unit tests.
- README with the behavior and the requirements.
