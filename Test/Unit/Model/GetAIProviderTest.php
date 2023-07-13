<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Exception\AIProviderNotFoundException;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Creatuity\AIContent\Model\GetAIProvider;
use Creatuity\AIContentOpenAI\Model\AIProvider\OpenAIProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetAIProviderTest extends TestCase
{
    private readonly AiContentGeneralConfig|MockObject $aiContentGeneralConfig;

    protected function setUp(): void
    {
        $this->aiContentGeneralConfig = $this->createMock(AiContentGeneralConfig::class);
    }

    public function testExecute(): void
    {
        $storeId = 1;
        $providerName = 'openai';
        $this->aiContentGeneralConfig
            ->expects($this->once())
            ->method('getAIProvider')
            ->with($storeId)
            ->willReturn($providerName);

        $providerA = $this->createMock(AIProviderInterface::class);
        $providerA->expects($this->once())->method('isApplicable')->with($providerName)->willReturn(false);

        $providerB = $this->createMock(OpenAIProvider::class);
        $providerB->expects($this->once())->method('isApplicable')->with($providerName)->willReturn(true);

        $object = new GetAIProvider($this->aiContentGeneralConfig, [$providerA, $providerB]);
        $this->assertSame($providerB, $object->execute($storeId));
    }

    public function testExecuteWrongProviderType(): void
    {
        $storeId = 1;
        $providerName = 'openai';
        $this->aiContentGeneralConfig
            ->expects($this->once())
            ->method('getAIProvider')
            ->with($storeId)
            ->willReturn($providerName);

        $providerA = $this->getMockBuilder(\stdclass::class)
            ->addMethods(['isApplicable'])
            ->getMock();
        $providerA->expects($this->never())->method('isApplicable');

        $providerB = $this->getMockBuilder(\stdclass::class)
            ->addMethods(['isApplicable'])
            ->getMock();

        $providerB->expects($this->never())->method('isApplicable');

        $this->expectException(AIProviderNotFoundException::class);
        $this->expectExceptionMessage((string) __('%1 provider is not supported', $providerName));
        (new GetAIProvider($this->aiContentGeneralConfig, [$providerA, $providerB]))->execute($storeId);
    }
}