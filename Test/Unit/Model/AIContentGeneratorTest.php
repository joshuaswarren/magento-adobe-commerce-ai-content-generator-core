<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model;

use Creatuity\AIContent\Api\AITypedContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\ContentGeneratorNotFoundException;
use Creatuity\AIContent\Model\AIContentGenerator;
use Creatuity\AIContent\Model\AIContentGenerator\DefaultTypedContentGenerator;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AIContentGeneratorTest extends TestCase
{
    public function testExecute(): void
    {
        $expected = $this->createMock(AIResponseInterface::class);;
        $contentType = 'description';
        $generators = $this->mockGenerators($contentType, $expected);
        $specification = $this->createMock(SpecificationInterface::class);
        $specification
            ->expects($this->exactly(count($generators) - 1))
            ->method('getContentType')
            ->willReturn($contentType);
        $this->assertSame($expected, (new AIContentGenerator($generators))->execute($specification));
    }

    public function testExecuteWrongContentType(): void
    {
        $contentType = 'some wierd type';
        $generators = $this->mockGenerators($contentType, null);
        $specification = $this->createMock(SpecificationInterface::class);
        $specification
            ->expects($this->exactly(count($generators) + 1))
            ->method('getContentType')
            ->willReturn($contentType);

        $this->expectException(ContentGeneratorNotFoundException::class);
        $this->expectExceptionMessage(
            (string) __('Failed to generate content due to lack of "%1" generator', $contentType)
        );

        (new AIContentGenerator($generators))->execute($specification);
    }

    public function testExecuteWrongGeneratorProvided(): void
    {
        $contentType = 'description';

        $generatorA = $this->createMock(DefaultTypedContentGenerator::class);
        $generatorA->expects($this->once())->method('isApplicable')->with($contentType);

        $generatorB = $this->createMock(DefaultTypedContentGenerator::class);
        $generatorB->expects($this->once())->method('isApplicable')->with($contentType);

        $specification = $this->createMock(SpecificationInterface::class);
        $specification
            ->expects($this->exactly(2))
            ->method('getContentType')
            ->willReturn($contentType);

        $generatorC = $this->getMockBuilder(\stdclass::class)->getMock();

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage((string) __(
            'Generator %1 is not instance of %2',
            get_class($generatorC),
            AITypedContentGeneratorInterface::class
        ));

        (new AIContentGenerator([
            $generatorA,
            $generatorB,
            $generatorC
        ]))->execute($specification);
    }

    /**
     * @return AITypedContentGeneratorInterface[]|MockObject[]
     */
    private function mockGenerators(string $contentType, AIResponseInterface|MockObject|null $response): array
    {
        $generatorA = $this->createMock(DefaultTypedContentGenerator::class);
        $generatorB = $this->createMock(DefaultTypedContentGenerator::class);
        $generatorC = $this->createMock(DefaultTypedContentGenerator::class);

        $generatorA->expects($this->once())->method('isApplicable')->with($contentType)->willReturn(false);
        $generatorA->expects($this->never())->method('execute');

        $generatorB->expects($this->once())->method('isApplicable')->with($contentType)->willReturn((bool) $response);
        if ($response) {
            $generatorB->expects($this->exactly(1))->method('execute')->willReturn($response);
        } else {
            $generatorB->expects($this->never())->method('execute');
        }
        $generatorC->expects($this->exactly($response ? 0 : 1))->method('isApplicable')->with($contentType)->willReturn(false);
        $generatorC->expects($this->never())->method('execute');

        return [$generatorA, $generatorB, $generatorC];
    }
}