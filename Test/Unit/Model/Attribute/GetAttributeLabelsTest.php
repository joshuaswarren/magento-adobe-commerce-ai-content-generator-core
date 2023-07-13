<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetAttributeLabels;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GetAttributeLabelsTest extends TestCase
{
    private readonly Product|MockObject $productResource;
    private readonly LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->productResource = $this->createMock(Product::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testExecute(): void
    {
        $attrCodes = ['color', 'name', 'size'];
        $expected = [
            'color' => 'Color',
            'name' => 'Product Name',
            'size' => 'Size'
        ];

        $this->productResource
            ->expects($this->exactly(count($attrCodes)))
            ->method('getAttribute')
            ->willReturnCallback(fn($attrCode) => match ($attrCode) {
                'color' => $this->mockAttribute('Color'),
                'name' => $this->mockAttribute('Product Name'),
                'size' => $this->mockAttribute('Size')
            });
        $this->logger->expects($this->never())->method('error');
        $object = new GetAttributeLabels($this->productResource, $this->logger);
        $this->assertSame($expected, $object->execute($attrCodes));
    }

    public function testExecuteWithException(): void
    {
        $attrCodes = ['color', 'name', 'size'];
        $expected = [
            'color' => 'Color',
            'size' => 'Size'
        ];
        $exception = new \Exception('error');
        $this->productResource
            ->expects($this->exactly(count($attrCodes)))
            ->method('getAttribute')
            ->willReturnCallback(fn($attrCode) => match ($attrCode) {
                'color' => $this->mockAttribute('Color'),
                'name' => throw $exception,
                'size' => $this->mockAttribute('Size')
            });
        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with('Failed to find attribute name', ['exception' => $exception]);
        $object = new GetAttributeLabels($this->productResource, $this->logger);
        $this->assertSame($expected, $object->execute($attrCodes));
    }

    private function mockAttribute(string $label): Attribute|MockObject
    {
        $frontend = $this->createMock(AbstractFrontend::class);
        $frontend->expects($this->once())->method('getLabel')->willReturn($label);

        $attr = $this->createMock(Attribute::class);
        $attr->expects($this->once())->method('getFrontend')->willReturn($frontend);

        return $attr;
    }
}