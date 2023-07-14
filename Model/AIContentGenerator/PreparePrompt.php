<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Attribute\AttributesDataProviderInterface;

class PreparePrompt
{
    public const STOP = "\n\n";

    private const CONTENT_TYPES = [
        'short_description' => 'short description',
        'description' => 'description'
    ];

    public function __construct(
        private readonly AttributesDataProviderInterface $attributesDataProvider,
        private readonly array $contentTypes = self::CONTENT_TYPES
    ) {
    }

    public function generate(SpecificationInterface $specification, string $template): string
    {
        $prompt = (string) __(
            $template,
            [
                'type' => $this->contentTypes[$specification->getContentType()] ?? $specification->getContentType(),
                'max_len' => $specification->getMaxLength() ?: SpecificationInterface::MAX_LENGTH,
                'min_len' => $specification->getMinLength() ?: 0
            ]
        );
        $prompt .= "\n" . __('Product attributes:') . "\n";
        $prompt .= $this->attributesDataProvider->execute($specification);
        $prompt .= self::STOP;

        return $prompt;
    }
}