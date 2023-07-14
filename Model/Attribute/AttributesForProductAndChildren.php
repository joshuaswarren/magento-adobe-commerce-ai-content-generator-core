<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class AttributesForProductAndChildren implements AttributesDataProviderInterface
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly GetAttributeValuesWithLabelsForProductAndChildren $getAttrValuesWithLabels,
        private readonly AttributesValuesWithLabelsToString $attrsValuesWithLabelsToString
    ) {
    }

    public function execute(SpecificationInterface $specification): string
    {
        $attrCodes = $specification->getProductAttributes();
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect($attrCodes);
        $collection->addFieldToFilter('entity_id', $specification->getProductId());
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $collection->getFirstItem();

        return $this->attrsValuesWithLabelsToString->execute(
            $this->getAttrValuesWithLabels->execute($product, $attrCodes)
        );
    }
}