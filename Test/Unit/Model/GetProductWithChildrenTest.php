<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use Creatuity\AIContent\Model\GetProductWithChildren;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class GetProductWithChildrenTest extends TestCase
{
    private readonly CollectionFactory|MockObject $collectionFactory;
    private readonly GetDescriptionAttributes|MockObject $descriptionAttributes;
    private readonly MetadataPool|MockObject $metadataPool;

    protected function setUp(): void
    {
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->descriptionAttributes = $this->createMock(GetDescriptionAttributes::class);
        $this->metadataPool = $this->createMock(MetadataPool::class);
    }

    public function testExecute(): void
    {
        $id = 123;
        $linkField = 'row_id';
        $childrenIds = [[345, 678, 890]];
        $storeId = 1;
        $attrCodes = ['color', 'size', 'name'];

        $metaData = $this->createMock(EntityMetadataInterface::class);
        $metaData->expects($this->once())->method('getLinkField')->willReturn($linkField);
        $this->metadataPool
            ->expects($this->once())
            ->method('getMetadata')
            ->with(ProductInterface::class)
            ->willReturn($metaData);

        $typeInstance = $this->createMock(Configurable::class);
        $typeInstance->expects($this->once())->method('getChildrenIds')->with($id)->willReturn($childrenIds);
        $product = $this->createMock(Product::class);
        $product->expects($this->exactly(2))->method('getData')->with($linkField)->willReturn($id);
        $product->expects($this->once())->method('getStoreId')->willReturn($storeId);
        $product->expects($this->once())->method('getTypeInstance')->willReturn($typeInstance);

        $this->descriptionAttributes->expects($this->never())->method('execute');

        $childrenIds = array_merge([], ...$childrenIds);
        $childrenIds[] = $id;

        $collection = $this->createMock(Collection::class);
        $collection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with($linkField, ['in' => $childrenIds])
            ->willReturnSelf();
        $collection
            ->expects($this->once())
            ->method('addAttributeToSelect')
            ->with($attrCodes)
            ->willReturnSelf();
        $collection
            ->expects($this->once())
            ->method('setStoreId')
            ->with($storeId)
            ->willReturnSelf();

        $this->collectionFactory->expects($this->once())->method('create')->willReturn($collection);
        $object = new GetProductWithChildren(
            $this->collectionFactory,
            $this->descriptionAttributes,
            $this->metadataPool
        );
        $this->assertSame($collection, $object->execute($product, $attrCodes));
    }
}