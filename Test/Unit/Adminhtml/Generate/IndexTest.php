<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Adminhtml\Generate;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Controller\Adminhtml\Generate\Index;
use Creatuity\AIContent\Model\RequestProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    private readonly Context|MockObject $context;
    private readonly JsonFactory|MockObject $jsonResultFactory;
    private readonly RequestProcessor|MockObject $requestProcessor;
    private readonly RequestInterface|MockObject $request;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->jsonResultFactory = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->requestProcessor = $this->createMock(RequestProcessor::class);
        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testExecute(): void
    {
        $params = [
            SpecificationInterface::PRODUCT_ID => 1,
            SpecificationInterface::CONTENT_TYPE => 'description',
            SpecificationInterface::PRODUCT_ATTRIBUTES => []
        ];
        $generatedText = 'some text';
        $json = $this->createMock(Json::class);
        $this->request->expects($this->once())->method('getParam')->with('specification', [])->willReturn($params);
        $this->requestProcessor->expects($this->once())->method('execute')->with($params)->willReturn($generatedText);
        $json->expects($this->once())->method('setData')->with(['text' => $generatedText]);
        $this->jsonResultFactory->expects($this->once())->method('create')->willReturn($json);
        $index = new Index($this->context, $this->jsonResultFactory, $this->requestProcessor, $this->request);
        $result = $index->execute();
        $this->assertSame($json, $result);
    }
}