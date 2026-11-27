<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogProductOptionCompositeProduct\Test\Unit\Block;

use Infrangible\CatalogProductOptionCompositeProduct\Block\Quote\Item\Bundle\Products\Selection\Options\Type\Product;
use Infrangible\CatalogProductOptionProduct\Block\Product\View\Options\Type\Product as OptionRenderer;
use Infrangible\CatalogProductOptionProduct\Block\Product\View\Options\Type\ProductFactory as OptionRendererFactory;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Catalog\Model\Product\Option;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;

/**
 * Tests the renderer of the product page that renders an option with a product for a product of a bundle option.
 */
class ProductTest extends TestCase
{
    /** @var list<array{Option|null, CatalogProduct|null}> the option and main product of each created renderer */
    private array $renderers = [];

    /**
     * Returns the block with a factory that creates renderers which keep their option and main product.
     *
     * @return Product
     */
    private function createBlock(): Product
    {
        $factory = $this->createStub(OptionRendererFactory::class);
        $factory->method('create')->willReturnCallback(fn(): OptionRenderer => $this->createRenderer());

        return new Product($this->createStub(Context::class), $factory);
    }

    /**
     * Returns a renderer of the product page that keeps its option and main product.
     *
     * @return OptionRenderer
     */
    private function createRenderer(): OptionRenderer
    {
        $index = count($this->renderers);
        $this->renderers[ $index ] = [null, null];

        $renderer = $this->createStub(OptionRenderer::class);
        $renderer->method('setOption')->willReturnCallback(
            function (Option $option) use ($renderer, $index): OptionRenderer {
                $this->renderers[ $index ][ 0 ] = $option;

                return $renderer;
            }
        );
        $renderer->method('setProduct')->willReturnCallback(
            function (?CatalogProduct $product) use ($renderer, $index): OptionRenderer {
                $this->renderers[ $index ][ 1 ] = $product;

                return $renderer;
            }
        );
        $renderer->method('getOption')->willReturnCallback(fn(): ?Option => $this->renderers[ $index ][ 0 ]);
        $renderer->method('getMainProduct')->willReturnCallback(
            fn(): ?CatalogProduct => $this->renderers[ $index ][ 1 ]
        );

        return $renderer;
    }

    /**
     * Returns the block with a renderer whose cart item contains the value for the option, or nothing.
     *
     * @param string|null $value
     *
     * @return Product
     */
    private function createBlockWithValue(?string $value): Product
    {
        $renderer = $this->createStub(OptionRenderer::class);
        $renderer->method('isPreconfigured')->willReturn($value !== null && $value !== '0');
        $renderer->method('getPreconfiguredValue')->willReturn($value);

        $factory = $this->createStub(OptionRendererFactory::class);
        $factory->method('create')->willReturn($renderer);

        $block = new Product($this->createStub(Context::class), $factory);
        $block->setOption($this->createStub(Option::class));
        $block->setProduct($this->createStub(CatalogProduct::class));

        return $block;
    }

    public function testSelectedValueOfCartItem(): void
    {
        $block = $this->createBlockWithValue('53');

        $this->assertTrue($block->isSelected());
        $this->assertSame('53', $block->getSelectedValue());
    }

    public function testValueZeroWithoutProductInCartItem(): void
    {
        foreach ([null, '0'] as $value) {
            $block = $this->createBlockWithValue($value);

            $this->assertFalse($block->isSelected());
            $this->assertSame('0', $block->getSelectedValue());
        }
    }

    public function testRendererWithOptionAndProductOfBundleOption(): void
    {
        $option = $this->createStub(Option::class);
        $product = $this->createStub(CatalogProduct::class);

        $block = $this->createBlock();
        $block->setOption($option);
        $block->setProduct($product);

        $renderer = $block->getOptionRenderer();

        $this->assertSame([[$option, $product]], $this->renderers);
        $this->assertSame($renderer, $block->getOptionRenderer());
    }

    public function testNewRendererForNextOption(): void
    {
        $firstOption = $this->createStub(Option::class);
        $secondOption = $this->createStub(Option::class);
        $firstProduct = $this->createStub(CatalogProduct::class);
        $secondProduct = $this->createStub(CatalogProduct::class);

        $block = $this->createBlock();
        $block->setOption($firstOption);
        $block->setProduct($firstProduct);
        $block->getOptionRenderer();

        $block->setOption($secondOption);
        $block->getOptionRenderer();

        $block->setProduct($secondProduct);
        $block->getOptionRenderer();

        $this->assertSame(
            [[$firstOption, $firstProduct], [$secondOption, $firstProduct], [$secondOption, $secondProduct]],
            $this->renderers
        );
    }
}
