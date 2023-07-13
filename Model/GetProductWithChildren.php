<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\EntityManager\MetadataPool;

class GetProductWithChildren
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly GetDescriptionAttributes $descriptionAttributes,
        private readonly MetadataPool $metadataPool
    ) {
    }

    public function execute(Product $product, array $attrCodes = []): Collection
    {
        $linkField = $this->metadataPool->getMetadata(ProductInterface::class)->getLinkField();
        $childrenIds = $product->getTypeInstance()->getChildrenIds($product->getData($linkField));
        $childrenIds = array_merge([], ...$childrenIds);
        $childrenIds[] = $product->getData($linkField);
        $childrenIds = array_filter($childrenIds);
        $storeId = (int) $product->getStoreId();
        $collection = $this->collectionFactory->create();
        //@phpstan-ignore-next-line
        $collection
            ->addFieldToFilter($linkField, ['in' => $childrenIds])
            ->addAttributeToSelect($attrCodes ?: array_keys($this->descriptionAttributes->execute($storeId)))
            ->setStoreId($storeId);

        return $collection;
    }
}