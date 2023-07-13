<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Attribute\AttributesForProductAndChildren;
use Creatuity\AIContent\Model\Attribute\AttributesValuesWithLabelsToString;
use Creatuity\AIContent\Model\Attribute\GetAttributeValuesWithLabelsForProductAndChildren;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributesForProductAndChildrenTest extends TestCase
{
    private readonly ProductRepositoryInterface|MockObject $productRepository;
    private readonly GetAttributeValuesWithLabelsForProductAndChildren|MockObject $getAttrValuesWithLabels;
    private readonly AttributesValuesWithLabelsToString|MockObject $attrsValuesWithLabelsToString;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->getAttrValuesWithLabels = $this->createMock(GetAttributeValuesWithLabelsForProductAndChildren::class);
        $this->attrsValuesWithLabelsToString = $this->createMock(AttributesValuesWithLabelsToString::class);
    }

    public function testExecute(): void
    {
        $productId = 123;
        $attrCodes = ['color', 'name', 'size'];
        $attrValuesLabels = [
            'Color' => ['red', 'blue'],
            'Size' => ['XL', 'M', 'L'],
            'Product Name' => ['majtki']
        ];
        $expected = "Color: red, blue\nSize: XL, M, L\nProduct Name: majtki";
        $spec = $this->createMock(SpecificationInterface::class);
        $spec->expects($this->once())->method('getProductId')->willReturn($productId);
        $spec->expects($this->once())->method('getProductAttributes')->willReturn($attrCodes);

        $product = $this->createMock(Product::class);
        $this->productRepository
            ->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willReturn($product);

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
            $this->productRepository,
            $this->getAttrValuesWithLabels,
            $this->attrsValuesWithLabelsToString
        );

        $this->assertSame($expected, $object->execute($spec));
    }
}