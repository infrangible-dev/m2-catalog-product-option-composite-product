<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionCompositeProduct\Block\Quote\Item\Bundle\Products\Selection\Options\Type;

use Infrangible\CatalogProductOptionComposite\Block\Quote\Item\Bundle\Products\Selection\Options\Type\ItemBundleSelectionOptionInterface;
use Infrangible\CatalogProductOptionComposite\Traits\ItemBundleSelectionOption;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Product extends \Infrangible\CatalogProductOptionProduct\Block\Product\View\Options\Type\Product implements ItemBundleSelectionOptionInterface
{
    use ItemBundleSelectionOption;
}
