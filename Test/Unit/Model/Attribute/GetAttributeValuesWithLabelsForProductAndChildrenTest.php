<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetAttributeValuesWithLabelsForProductAndChildren;
use Creatuity\AIContent\Model\Attribute\RetrieveAttributesForProduct;
use Creatuity\AIContent\Model\GetProductWithChildren;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetAttributeValuesWithLabelsForProductAndChildrenTest extends TestCase
{
    private readonly GetProductWithChildren $getProductWithChildren;
    private readonly RetrieveAttributesForProduct $retrieveAttributesForProduct;

    protected function setUp(): void
    {
        $this->getProductWithChildren = $this->createMock(GetProductWithChildren::class);
        $this->retrieveAttributesForProduct = $this->createMock(RetrieveAttributesForProduct::class);
    }

    public function testExecuteEmptyAttrCodes(): void
    {
        $object = new GetAttributeValuesWithLabelsForProductAndChildren(
            $this->getProductWithChildren,
            $this->retrieveAttributesForProduct
        );
        $product = $this->createMock(Product::class);
        $this->assertSame([], $object->execute($product, []));
    }

    public function testExecute(): void
    {
        $attrCodes = ['color', 'size', 'name'];
        $product = $this->mockProduct();
        $products = [
            $this->mockProduct(),
            $this->mockProduct(),
            $this->mockProduct()
        ];
        $this->getProductWithChildren
            ->expects($this->once())
            ->method('execute')
            ->with($product, $attrCodes)
            ->willReturn($this->mockCollection($products));

        $this->retrieveAttributesForProduct
            ->expects($this->exactly(count($products)))
            ->method('execute')
            ->willReturnCallback(fn($attrCode) => match ($attrCode) {
                $products[0] => ['Color' => 'red', 'Size' => 'XL', 'Product Name' => 'majtki'],
                $products[1] => ['Color' => 'blue', 'Size' => 'M', 'Product Name' => 'spodnie'],
                $products[2] => ['Color' => 'orange', 'Size' => 'XXL', 'Product Name' => 'T-shirt']
            });

        $expected = [
            'Color' => [
                'red' => 'red',
                'blue' => 'blue',
                'orange' => 'orange'
            ],
            'Size' => [
                'XL' => 'XL',
                'M' => 'M',
                'XXL' => 'XXL'
            ],
            'Product Name' => [
                'majtki' => 'majtki',
                'spodnie' => 'spodnie',
                'T-shirt' => 'T-shirt'
            ]
        ];
        $object = new GetAttributeValuesWithLabelsForProductAndChildren(
            $this->getProductWithChildren,
            $this->retrieveAttributesForProduct
        );
        $this->assertSame($expected, $object->execute($product, $attrCodes));
    }

    private function mockCollection(array $products): Collection|MockObject
    {
        $collection = $this->createMock(Collection::class);
        $iterator = new \ArrayIterator($products);
        $collection->expects($this->once())->method('getIterator')->willReturn($iterator);

        return $collection;
    }

    private function mockProduct(): Product|MockObject
    {
        return $this->createMock(Product::class);
    }
}