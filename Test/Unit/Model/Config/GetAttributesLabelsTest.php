<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Config;

use Creatuity\AIContent\Exception\NoDescriptionAttributesChosenException;
use Creatuity\AIContent\Model\Attribute\GetAttributeLabels;
use Creatuity\AIContent\Model\Config\GetAttributesLabels;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetAttributesLabelsTest extends TestCase
{
    private readonly GetAttributeLabels|MockObject $getAttributeLabels;

    protected function setUp(): void
    {
        $this->getAttributeLabels = $this->createMock(GetAttributeLabels::class);
    }


    public function testExecute(): void
    {
        $attrCodes = ['color', 'size', 'name'];
        $expected = [
            'color' => 'Color',
            'size' => 'Size',
            'name' => 'Product Name'
        ];
        $this->getAttributeLabels
            ->expects($this->once())
            ->method('execute')
            ->with($attrCodes)
            ->willReturn($expected);

        $this->assertSame($expected, $this->getObject()->get($attrCodes));
    }

    public function testExecuteEmptyAttributes(): void
    {
        $this->getAttributeLabels->expects($this->never())->method('execute');

        $this->expectException(NoDescriptionAttributesChosenException::class);
        $this->getObject()->get([]);
    }

    private function getObject(): GetAttributesLabels
    {
        return new GetAttributesLabels($this->getAttributeLabels);
    }
}