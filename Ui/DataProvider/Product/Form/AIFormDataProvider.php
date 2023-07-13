<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
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
        return [
            '' => [
                'product_id' => $this->request->getParam('product_id'),
                'description_max_length' => SpecificationInterface::DESCRIPTION_DEFAULT_MAX_LENGTH,
                'short_description_max_length' => SpecificationInterface::SHORT_DESCRIPTION_DEFAULT_MAX_LENGTH
            ],
        ];
    }
}