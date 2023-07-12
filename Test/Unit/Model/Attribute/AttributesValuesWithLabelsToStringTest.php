<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Attribute;

use Creatuity\AIContent\Model\Attribute\AttributesValuesWithLabelsToString;
use PHPUnit\Framework\TestCase;

class AttributesValuesWithLabelsToStringTest extends TestCase
{
    public function testExecuteEmptyData(): void
    {
        $this->assertSame('', (new AttributesValuesWithLabelsToString())->execute([]));
    }

    public function testExecuteWithData(): void
    {
        $attrValuesLabels = [
            'Color' => ['red', 'blue'],
            'Size' => ['XL', 'M', 'L'],
            'Product Name' => ['majtki']
        ];
        $expected = "Color: red, blue\nSize: XL, M, L\nProduct Name: majtki";
        $this->assertSame($expected, (new AttributesValuesWithLabelsToString())->execute($attrValuesLabels));
    }
}