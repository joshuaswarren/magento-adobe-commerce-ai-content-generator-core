<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Attribute\AttributesForProductAndChildren;
use Creatuity\AIContent\Model\Attribute\AttributesValuesWithLabelsToString;
use Creatuity\AIContent\Model\Attribute\GetAttributeValuesWithLabelsForProductAndChildren;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributesForProductAndChildrenTest extends TestCase
{
    private readonly CollectionFactory|MockObject $collectionFactory;
    private readonly GetAttributeValuesWithLabelsForProductAndChildren|MockObject $getAttrValuesWithLabels;
    private readonly AttributesValuesWithLabelsToString|MockObject $attrsValuesWithLabelsToString;

    protected function setUp(): void
    {
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->getAttrValuesWithLabels = $this->createMock(GetAttributeValuesWithLabelsForProductAndChildren::class);
        $this->attrsValuesWithLabelsToString = $this->createMock(AttributesValuesWithLabelsToString::class);
    }

    public function testExecute(): void
    {
        $productId = 123;
        $storeId = 1;
        $attrCodes = ['color', 'name', 'size'];
        $attrValuesLabels = [
            'Color' => ['red', 'blue'],
            'Size' => ['XL', 'M', 'L'],
            'Product Name' => ['majtki']
        ];
        $expected = "Color: red, blue\nSize: XL, M, L\nProduct Name: majtki";
        $spec = $this->createMock(SpecificationInterface::class);
        $spec->expects($this->once())->method('getProductId')->willReturn($productId);
        $spec->expects($this->once())->method('getStoreId')->willReturn($storeId);
        $spec->expects($this->once())->method('getProductAttributes')->willReturn($attrCodes);

        $product = $this->createMock(Product::class);

        $collection = $this->createMock(Collection::class);
        $collection
            ->expects($this->once())
            ->method('addFieldToSelect')
            ->with($attrCodes)
            ->willReturnSelf();
        $collection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with('entity_id', ['eq' => $productId])
            ->willReturnSelf();
        $collection
            ->expects($this->once())
            ->method('setStoreId')
            ->with($storeId)
            ->willReturnSelf();
        $collection
            ->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($product);

        $this->collectionFactory->expects($this->once())->method('create')->willReturn($collection);

        $this->getAttrValuesWithLabels
            ->expects($this->once())
            ->method('execute')
            ->with($product, $attrCodes)
            ->willReturn($attrValuesLabels);

        $this->attrsValuesWithLabelsToString
            ->expects($this->once())
            ->method('execute')
            ->with($attrValuesLabels)
            ->willReturn($expected);

        $object = new AttributesForProductAndChildren(
            $this->collectionFactory,
            $this->getAttrValuesWithLabels,
            $this->attrsValuesWithLabelsToString
        );

        $this->assertSame($expected, $object->execute($spec));
    }
}