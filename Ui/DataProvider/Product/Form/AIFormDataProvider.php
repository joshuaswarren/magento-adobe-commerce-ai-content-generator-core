<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Ui\DataProvider\Product\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class AIFormDataProvider extends AbstractDataProvider
{
    /**
     * CustomDataProvider constructor.
     *
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
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

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [
            '' => []
        ];
    }
}