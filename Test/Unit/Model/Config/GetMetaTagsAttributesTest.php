<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\Config;

use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Creatuity\AIContent\Model\Config\GetMetaTagsAttributes;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetMetaTagsAttributesTest extends TestCase
{
    private readonly AiContentGeneralConfig|MockObject $aiContentGeneralConfig;

    protected function setUp(): void
    {
        $this->aiContentGeneralConfig = $this->createMock(AiContentGeneralConfig::class);
    }

    public function testExecute(): void
    {
        $storeId = 1;
        $expected = ['color', 'size', 'name'];
        $this->aiContentGeneralConfig
            ->expects($this->once())
            ->method('getMetaAttributes')
            ->with($storeId)
            ->willReturn($expected);
        $this->assertSame($expected, (new GetMetaTagsAttributes($this->aiContentGeneralConfig))->execute($storeId));
    }
}