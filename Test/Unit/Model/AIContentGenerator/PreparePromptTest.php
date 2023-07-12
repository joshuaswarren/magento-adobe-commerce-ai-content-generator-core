<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Test\Unit\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\AIContentGenerator\PreparePrompt;
use Creatuity\AIContent\Model\Attribute\AttributesDataProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PreparePromptTest extends TestCase
{
    private readonly AttributesDataProviderInterface|MockObject $attributesDataProvider;

    protected function setUp(): void
    {
        $this->attributesDataProvider = $this->createMock(AttributesDataProviderInterface::class);
    }

    public function testGenerate(): void
    {
        $type = 'description';
        $minLength = 10;
        $maxLength = 150;
        $attributeText = 'Color: Red';
        $template = 'Generate %type for website presenting the products with attributes given below. ' .
            'Text should be at least %min_len characters and no longer than %max_len characters';
        $prompt = (string) __(
            $template,
            [
                'type' => $type,
                'min_len' => $minLength,
                'max_len' => $maxLength
            ]
        );
        $prompt .= "\n\n" . __('Product attributes:') . "\n";
        $prompt .= $attributeText;
        $this->attributesDataProvider->expects($this->once())->method('execute')->willReturn($attributeText);
        $specification = $this->createMock(SpecificationInterface::class);
        $specification->expects($this->once())->method('getContentType')->willReturn($type);
        $specification->expects($this->once())->method('getMinLength')->willReturn($minLength);
        $specification->expects($this->once())->method('getMaxLength')->willReturn($maxLength);
        $object = new PreparePrompt($this->attributesDataProvider);
        $this->assertSame($prompt, $object->generate($specification, $template));
    }
}