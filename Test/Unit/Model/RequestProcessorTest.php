<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\RequestProcessor;
use Creatuity\AIContent\Model\SpecificationHydrator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestProcessorTest extends TestCase
{
    private readonly SpecificationHydrator|MockObject $specificationHydrator;
    private readonly AIContentGeneratorInterface|MockObject $AIContentGenerator;

    protected function setUp(): void
    {
        $this->specificationHydrator = $this->createMock(SpecificationHydrator::class);
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
        $this->specificationHydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($params)
            ->willReturn($specification);
        $expected = $this->createMock(AIResponseInterface::class);
        $this->AIContentGenerator->expects($this->once())->method('execute')->with($specification)->willReturn($expected);
        $object = new RequestProcessor($this->AIContentGenerator, $this->specificationHydrator);
        $this->assertSame($expected, $object->execute($params));
    }
}