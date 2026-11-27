# Infrangible Catalog Product Option Composite Product

Shows the options with a product of
[Catalog Product Option Product](https://github.com/infrangible-dev/m2-catalog-product-option-product) on the page of
[Catalog Product Option Composite](https://github.com/infrangible-dev/m2-catalog-product-option-composite) where
customers change the custom options of a bundle item in the cart.

## Behavior

- Options of the type "Product" that are available for a product of a bundle option are listed below this product,
  like on the product page: a simple product as a checkbox with its name and price, a configurable product with its
  swatches to choose the variant.
- The options show the product or variant that is in the cart item: the checkbox is checked and the swatches select the
  variant.
- Changed options are saved with the other custom options when the customer updates the bundle item.

There are no settings.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.6 or newer
- PHP 8.1 or newer
- Catalog Product Option Composite 6.0 and Catalog Product Option Product 2.0, which are installed with the module

## Installation

```bash
composer require infrangible/m2-catalog-product-option-composite-product
bin/magento module:enable Infrangible_CatalogProductOptionCompositeProduct
bin/magento setup:upgrade
```

In production mode, also run `bin/magento setup:di:compile` and `bin/magento setup:static-content:deploy`.

## License

Infrangible Catalog Product Option Composite Product is licensed under the MIT License - see the LICENSE file for
details.
