<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\AIContentGenerator;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterfaceFactory;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Model\AIContentGenerator\GenerateContent;
use Creatuity\AIContent\Model\GetAIProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateContentTest extends TestCase
{
    private readonly GetAIProvider|MockObject $provider;
    private readonly AIRequestInterfaceFactory|MockObject $AIRequestInterfaceFactory;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(GetAIProvider::class);
        $this->AIRequestInterfaceFactory = $this->getMockBuilder(AIRequestInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
    }

    public function testExecute(): void
    {
        $storeId = 1;
        $content = 'Some content';
        $prompt = 'Some text';
        $apiRequest = $this->createMock(AIRequestInterface::class);
        $this->AIRequestInterfaceFactory
            ->expects($this->once())
            ->method('create')
            ->with(['data' => ['input' => $prompt]])
            ->willReturn($apiRequest);
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $apiResponse->expects($this->once())->method('getContent')->willReturn($content);
        $provider = $this->createMock(AIProviderInterface::class);
        $provider->expects($this->once())->method('call')->with($apiRequest)->willReturn($apiResponse);
        $this->provider->expects($this->once())->method('execute')->with($storeId)->willReturn($provider);
        $object = new GenerateContent($this->provider, $this->AIRequestInterfaceFactory);
        $this->assertSame($content, $object->execute($prompt, $storeId));
    }
}