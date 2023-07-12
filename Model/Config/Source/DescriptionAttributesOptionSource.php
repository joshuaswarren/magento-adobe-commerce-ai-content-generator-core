<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config\Source;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterfaceFactory;
use Magento\Framework\Data\OptionSourceInterface;

class DescriptionAttributesOptionSource implements OptionSourceInterface
{
    public function __construct(
        private readonly AttributeRepositoryInterface $attributeRepository,
        private readonly SearchCriteriaInterfaceFactory $searchCriteriaFactory
    ) {
    }

    /**
     * @return string[][]
     */
    public function toOptionArray(): array
    {
        $attributes = $this->attributeRepository
            ->getList(Product::ENTITY, $this->searchCriteriaFactory->create());

        $options = [];
        foreach ($attributes->getItems() as $attribute) {
            $options[] = ['label' => $attribute->getDefaultFrontendLabel(), 'value' => $attribute->getAttributeCode()];
        }

        usort(
            $options, function ($a, $b) {
            return strcmp((string) $a['label'], (string) $b['label']);
        });

        return $options;
    }
}