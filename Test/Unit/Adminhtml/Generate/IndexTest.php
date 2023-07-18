<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Adminhtml\Generate;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Controller\Adminhtml\Generate\Index;
use Creatuity\AIContent\Model\RequestProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class IndexTest extends TestCase
{
    private readonly Context|MockObject $context;
    private readonly JsonFactory|MockObject $jsonResultFactory;
    private readonly RequestProcessor|MockObject $requestProcessor;
    private readonly RequestInterface|MockObject $request;
    private readonly LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->jsonResultFactory = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->requestProcessor = $this->createMock(RequestProcessor::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testExecute(): void
    {
        $params = [
            SpecificationInterface::PRODUCT_ID => 1,
            SpecificationInterface::CONTENT_TYPE => 'description',
            SpecificationInterface::PRODUCT_ATTRIBUTES => []
        ];
        $generatedText = ['text 1', 'text 2'];
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $apiResponse->expects($this->once())->method('getChoices')->willReturn($generatedText);
        $json = $this->createMock(Json::class);
        $this->request->expects($this->once())->method('getParam')->with('specification', [])->willReturn($params);
        $this->requestProcessor->expects($this->once())->method('execute')->with($params)->willReturn($apiResponse);
        $json->expects($this->once())->method('setData')->with(['success' => true, 'choices' => $generatedText]);
        $this->jsonResultFactory->expects($this->once())->method('create')->willReturn($json);
        $index = new Index(
            $this->context,
            $this->jsonResultFactory,
            $this->requestProcessor,
            $this->request,
            $this->logger
        );
        $result = $index->execute();
        $this->assertSame($json, $result);
    }
}