<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterfaceFactory;
use Creatuity\AIContent\Model\RequestProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestProcessorTest extends TestCase
{
    private readonly SpecificationInterfaceFactory|MockObject $specificationFactory;
    private readonly AIContentGeneratorInterface|MockObject $AIContentGenerator;

    protected function setUp(): void
    {
        $this->specificationFactory = $this->getMockBuilder(SpecificationInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->AIContentGenerator = $this->createMock(AIContentGeneratorInterface::class);
    }

    public function testExecute(): void
    {
        $params = [
            SpecificationInterface::CONTENT_TYPE => 'description',
            SpecificationInterface::PRODUCT_ID => 1,
            SpecificationInterface::PRODUCT_ATTRIBUTES => ['color', 'size', 'name']
        ];

        $specification = $this->createMock(SpecificationInterface::class);
        $this->specificationFactory
            ->expects($this->once())
            ->method('create')
            ->with(['data' => $params])->willReturn($specification);
        $expected = 'Some text';
        $this->AIContentGenerator->expects($this->once())->method('execute')->with($specification)->willReturn($expected);
        $object = new RequestProcessor($this->specificationFactory, $this->AIContentGenerator);
        $this->assertSame($expected, $object->execute($params));
    }
}