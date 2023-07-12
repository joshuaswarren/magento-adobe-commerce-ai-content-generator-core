<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\GetAttributeOptionValue;
use Creatuity\AIContent\Model\Attribute\GetAttributeOptionValueFormatted;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetAttributeOptionValueFormattedTest extends TestCase
{
    private readonly GetAttributeOptionValue|MockObject $getAttributeOptionValue;

    protected function setUp(): void
    {
        $this->getAttributeOptionValue = $this->createMock(GetAttributeOptionValue::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testExecute(string $attCode, int|string $option, string $optionValue, ?string $expected)
    {
        $this->getAttributeOptionValue
            ->expects($this->once())
            ->method('execute')
            ->with($attCode, $option)
            ->willReturn($optionValue);

        $this->assertSame(
            $expected,
            (new GetAttributeOptionValueFormatted($this->getAttributeOptionValue))->execute($attCode, $option)
        );
    }

    private function dataProvider(): array
    {
        return [
            ['code_1', '123', 'value 1', 'value 1'],
            ['code_2', 123, 'value 2', 'value 2'],
            ['code_3', 123, '', null]
        ];
    }
}