<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use Creatuity\AIContent\Model\Config\GetMetaTagsAttributes;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class AIFormDataProvider extends AbstractDataProvider
{
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly RequestInterface $request,
        private readonly GetDescriptionAttributes $getDescriptionAttributes,
        private readonly GetMetaTagsAttributes $getMetaTagsAttributes,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        $descriptionAttributes = $this->getDescriptionAttributes->execute((int)$this->request->getParam('store'));
        $metaTagsAttributes = $this->getMetaTagsAttributes->execute((int)$this->request->getParam('store'));

        return [
            '' => [
                'product_id' => $this->request->getParam('product_id'),
                'description_max_length' => SpecificationInterface::DESCRIPTION_DEFAULT_MAX_LENGTH,
                'short_description_max_length' => SpecificationInterface::SHORT_DESCRIPTION_DEFAULT_MAX_LENGTH,
                'store_id' => $this->request->getParam('store', 0),
                'product_description_attributes' => $descriptionAttributes,
                'product_meta_tags_attributes' => $metaTagsAttributes
            ],
        ];
    }
}