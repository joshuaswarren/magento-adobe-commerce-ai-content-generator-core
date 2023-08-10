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
        $expected = "<attribute>Color: red, blue</attribute>\n<attribute>Size: XL, M, L</attribute>\n<attribute>Product Name: majtki</attribute>";
        $this->assertSame($expected, (new AttributesValuesWithLabelsToString())->execute($attrValuesLabels));
    }
}