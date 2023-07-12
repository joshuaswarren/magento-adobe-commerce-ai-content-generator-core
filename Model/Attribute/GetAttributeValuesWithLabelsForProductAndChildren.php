<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Creatuity\AIContent\Model\GetProductWithChildren;
use Magento\Catalog\Model\Product;

class GetAttributeValuesWithLabelsForProductAndChildren
{
    public function __construct(
        private readonly GetProductWithChildren $getProductWithChildren,
        private readonly RetrieveAttributesForProduct $retrieveAttributesForProduct
    ) {
    }

    public function execute(Product $product, array $attrCodes): array
    {
        if (!$attrCodes) {
            return [];
        }

        $collection = $this->getProductWithChildren->execute($product, $attrCodes);
        $data = [];
        foreach ($collection as $product) {
            $attrLabelValues = $this->retrieveAttributesForProduct->execute($product, $attrCodes);
            foreach ($attrLabelValues as $label => $value) {
                $data[$label][$value] = $value;
            }
        }

        return $data;
    }
}