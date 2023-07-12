<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class AttributesForProductAndChildren implements AttributesDataProviderInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly GetAttributeValuesWithLabelsForProductAndChildren $getAttrValuesWithLabels,
        private readonly AttributesValuesWithLabelsToString $attrsValuesWithLabelsToString
    ) {
    }

    public function execute(SpecificationInterface $specification): string
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->getById($specification->getProductId());
        $attrCodes = $specification->getProductAttributes();

        return $this->attrsValuesWithLabelsToString->execute(
            $this->getAttrValuesWithLabels->execute($product, $attrCodes)
        );
    }
}