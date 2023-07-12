<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Config;

use Creatuity\AIContent\Exception\NoDescriptionAttributesChosenException;
use Creatuity\AIContent\Model\Attribute\GetAttributeLabels;
use Creatuity\AIContent\Model\Config\GetAttributesLabels;
use Creatuity\AIContent\Model\Config\GetDescriptionAttributes;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetAttributesLabelsTest extends TestCase
{
    private readonly GetDescriptionAttributes|MockObject $getDescriptionAttributes;
    private readonly GetAttributeLabels|MockObject $getAttributeLabels;

    protected function setUp(): void
    {
        $this->getDescriptionAttributes = $this->createMock(GetDescriptionAttributes::class);
        $this->getAttributeLabels = $this->createMock(GetAttributeLabels::class);
    }

    public function testExecuteDefaultAttributeCodes(): void
    {
        $storeId = 1;
        $attrCodes = ['color', 'size', 'name'];
        $this->getDescriptionAttributes
            ->expects($this->once())
            ->method('execute')
            ->with($storeId)
            ->willReturn($attrCodes);
        $expected = [
            'color' => 'Color',
            'size' => 'Size',
            'name' => 'Product Name'
        ];
        $this->getAttributeLabels->expects($this->once())->method('execute')->with($attrCodes)->willReturn($expected);
        $this->assertSame($expected, $this->getObject()->get([], $storeId));
    }

    public function testExecuteCustomAttributeCodes(): void
    {
        $storeId = 1;
        $attrCodes = ['color', 'size', 'name'];
        $this->getDescriptionAttributes->expects($this->never())->method('execute');
        $expected = [
            'color' => 'Color',
            'size' => 'Size',
            'name' => 'Product Name'
        ];
        $this->getAttributeLabels->expects($this->once())->method('execute')->with($attrCodes)->willReturn($expected);
        $this->assertSame($expected, $this->getObject()->get($attrCodes, $storeId));
    }

    public function testExecuteEmptyAttributes(): void
    {
        $this->getDescriptionAttributes
            ->expects($this->once())
            ->method('execute')
            ->with(null)
            ->willReturn([]);
        $this->getAttributeLabels->expects($this->never())->method('execute');

        $this->expectException(NoDescriptionAttributesChosenException::class);
        $this->getObject()->get();
    }

    private function getObject(): GetAttributesLabels
    {
        return new GetAttributesLabels(
            $this->getDescriptionAttributes,
            $this->getAttributeLabels
        );
    }
}