<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogProductOptionCompositeProduct\Block\Quote\Item\Bundle\Products\Selection\Options\Type;

use Infrangible\CatalogProductOptionComposite\Block\Quote\Item\Bundle\Products\Selection\Options\Type\DefaultType;
use Infrangible\CatalogProductOptionProduct\Block\Product\View\Options\Type\Product as OptionRenderer;
use Infrangible\CatalogProductOptionProduct\Block\Product\View\Options\Type\ProductFactory as OptionRendererFactory;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Framework\View\Element\Template\Context;

/**
 * Renders an option with a product of Catalog Product Option Product for a product of a bundle option in the cart.
 *
 * The prices, swatches and preconfigured values come from the renderer of the product page, with the product of the
 * bundle option as its main product; that product has the values of the cart item as preconfigured values.
 */
class Product extends DefaultType
{
    /** @var OptionRenderer|null */
    private ?OptionRenderer $optionRenderer = null;

    /**
     * @param Context $context
     * @param OptionRendererFactory $optionRendererFactory
     * @param array $data
     * @phpstan-param array<string, mixed> $data
     */
    public function __construct(
        Context $context,
        protected OptionRendererFactory $optionRendererFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Returns the renderer of the product page for the option and the product of the bundle option.
     *
     * The block renders one option after the other, so the renderer is created again when the option or product
     * changed.
     *
     * @return OptionRenderer
     */
    public function getOptionRenderer(): OptionRenderer
    {
        $option = $this->getOption();
        $product = $this->getProduct();

        if ($this->optionRenderer === null || $this->optionRenderer->getOption() !== $option ||
            $this->optionRenderer->getMainProduct() !== $product) {

            $optionRenderer = $this->optionRendererFactory->create();

            $optionRenderer->setOption($option);
            $optionRenderer->setProduct($product);

            $this->optionRenderer = $optionRenderer;
        }

        return $this->optionRenderer;
    }

    /**
     * Returns the product of the option, null if it has none.
     *
     * @return CatalogProduct|null
     */
    public function getOptionProduct(): ?CatalogProduct
    {
        return $this->getOptionRenderer()->getProduct();
    }

    /**
     * Checks if the cart item contains a product of the option.
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->getOptionRenderer()->isPreconfigured($this->getOption());
    }

    /**
     * Returns the value of the field of the option: the ID of the product or variant in the cart item, otherwise "0".
     *
     * @return string
     */
    public function getSelectedValue(): string
    {
        $value = $this->getOptionRenderer()->getPreconfiguredValue($this->getOption());

        return $this->isSelected() && is_scalar($value) ? (string)$value : '0';
    }

    /**
     * Checks if the option is required.
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return (bool)$this->getOption()->getIsRequire();
    }

    /**
     * Returns the price of the option: the price entered for it, or the final price of its product.
     *
     * @return float
     */
    public function getOptionPrice(): float
    {
        return $this->getOptionRenderer()->getOptionPrice();
    }
}
