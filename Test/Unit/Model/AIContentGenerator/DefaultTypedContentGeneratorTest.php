<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\UnsupportedContentTypeException;
use Creatuity\AIContent\Model\AIContentGenerator\DefaultTypedContentGenerator;
use Creatuity\AIContent\Model\AIContentGenerator\GenerateContent;
use Creatuity\AIContent\Model\AIContentGenerator\PreparePrompt;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DefaultTypedContentGeneratorTest extends TestCase
{
    private readonly GenerateContent|MockObject $generateContent;
    private readonly PreparePrompt|MockObject $preparePrompt;

    protected function setUp(): void
    {
        $this->generateContent = $this->createMock(GenerateContent::class);
        $this->preparePrompt = $this->createMock(PreparePrompt::class);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteNoAttributes(string $type, string $promptTemplate): void
    {
        $specification = $this->mockSpecification($type);
        $prompt = 'Some prompt';
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $this->preparePrompt->expects($this->once())->method('generate')->with($specification, $promptTemplate)->willReturn($prompt);
        $this->generateContent->expects($this->once())->method('execute')->with($prompt)->willReturn($apiResponse);
        $object = $this->getObject($type, $promptTemplate);
        $result = $object->execute($specification);
        $this->assertSame($apiResponse, $result);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteWithAttributes(string $type, string $promptTemplate): void
    {
        $attrs = ['color', 'size'];
        $specification = $this->mockSpecification($type, $attrs);
        $prompt = 'Some prompt';
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $this->preparePrompt->expects($this->once())->method('generate')->with($specification, $promptTemplate)->willReturn($prompt);
        $this->generateContent->expects($this->once())->method('execute')->with($prompt)->willReturn($apiResponse);
        $object = $this->getObject($type, $promptTemplate, $attrs);
        $result = $object->execute($specification);
        $this->assertSame($apiResponse, $result);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteExceptionExpected(string $type, string $promptTemplate): void
    {
        $this->expectException(UnsupportedContentTypeException::class);
        $specification = $this->mockSpecification($type);
        $this->preparePrompt->expects($this->never())->method('generate');
        $this->generateContent->expects($this->never())->method('execute');
        $this->getObject('other type', $promptTemplate)->execute($specification);
    }

    public function testGetType(): void
    {
        $type = 'description';
        $this->assertSame($type, $this->getObject($type, 'Some text')->getType());
    }

    public function testGetPromptTemplate(): void
    {
        $promptTemplate = 'Some text';
        $this->assertSame($promptTemplate, $this->getObject('description', $promptTemplate)->getPromptTemplate());
    }

    /**
     * @dataProvider isApplicableDataProvider
     */
    public function testIsApplicable(string $type, string $typeToCompare, bool $expected): void
    {
        $this->assertSame($expected, $this->getObject($type, 'Some text')->isApplicable($typeToCompare));
    }

    private function mockSpecification(string $contentType, array $attributes = []): SpecificationInterface|MockObject
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $specification->expects($this->atLeast(1))->method('getContentType')->willReturn($contentType);
        if ($attributes) {
            $specification->expects($this->once())->method('setProductAttributes')->with($attributes);
        } else {
            $specification->expects($this->never())->method('setProductAttributes');
        }

        return $specification;
    }

    private function isApplicableDataProvider(): array
    {
        return [
            ['description', 'description', true],
            ['description', 'meta-title', false]
        ];
    }

    private function executeDataProvider(): array
    {
        return [
            ['description', 'Some prompt template'],
            ['meta-title', 'Some other prompt template']
        ];
    }

    private function getObject(string $type, string $promptTemplate, array $attributes = []): DefaultTypedContentGenerator
    {
        return new DefaultTypedContentGenerator(
            $this->generateContent,
            $this->preparePrompt,
            $type,
            $promptTemplate,
            $attributes
        );
    }
}