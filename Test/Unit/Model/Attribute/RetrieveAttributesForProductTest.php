<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetAttributeOptionValueFormatted;
use Creatuity\AIContent\Model\Attribute\RetrieveAttributesForProduct;
use Creatuity\AIContent\Model\Config\GetAttributesLabels;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\callback;

class RetrieveAttributesForProductTest extends TestCase
{
    private readonly GetAttributesLabels $getAttributesLabels;
    private readonly GetAttributeOptionValueFormatted $getAttributeOptionValueFormatted;

    protected function setUp(): void
    {
        $this->getAttributesLabels = $this->createMock(GetAttributesLabels::class);
        $this->getAttributeOptionValueFormatted = $this->createMock(GetAttributeOptionValueFormatted::class);
    }

    public function testExecute(): void
    {
        $attrCodes = ['color', 'size', 'name'];
        $storeId = 1;

        $this->getAttributesLabels
            ->expects($this->once())
            ->method('get')
            ->with($attrCodes, $storeId)
            ->willReturn([
                'color' => 'Color',
                'size' => 'Size',
                'name' => 'Product Name'
            ]);

        $product = $this->createMock(Product::class);
        $product->expects($this->once())->method('getStoreId')->willReturn($storeId);
        $product
            ->expects($this->exactly(count($attrCodes)))
            ->method('getData')
            ->willReturnCallback(fn($attrCode) => match ($attrCode) {
                'color' => 'red',
                'size' => 'XL',
                'name' => 'T-Shirt'
            });

        $this->getAttributeOptionValueFormatted
            ->expects($this->exactly(count($attrCodes)))
            ->method('execute')
            ->willReturnCallback(fn($attrCode, $val) => match ([$attrCode, $val]) {
                ['color', 'red'] => 'Red',
                ['size', 'XL'] => 'XL',
                ['name', 'T-Shirt'] => 'T-Shirt',
            });
        $expected = [
            'Color' => 'Red',
            'Size' => 'XL',
            'Product Name' => 'T-Shirt'
        ];

        $object = new RetrieveAttributesForProduct(
            $this->getAttributesLabels,
            $this->getAttributeOptionValueFormatted
        );
        $this->assertSame($expected, $object->execute($product, $attrCodes));
    }
}