<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator;

use Creatuity\AIContent\Api\AITypedContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\UnsupportedContentTypeException;

class DefaultTypedContentGenerator implements AITypedContentGeneratorInterface
{
    public function __construct(
        private readonly GenerateContent $generateContent,
        private readonly PreparePrompt $preparePrompt,
        private readonly string $type,
        private readonly string $promptTemplate,
        private readonly array $attributes = []
    ) {
    }

    public function execute(SpecificationInterface $specification): string
    {
        if ($this->type !== $specification->getContentType()) {
            throw new UnsupportedContentTypeException(
                __('"%1" generating is not supported by %2', $this->type, static::class)
            );
        }

        if ($this->attributes) {
            $specification->setProductAttributes($this->attributes);
        }

        $prompt = $this->preparePrompt->generate($specification, $this->promptTemplate);

        return $this->generateContent->execute($prompt);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPromptTemplate(): string
    {
        return $this->promptTemplate;
    }

    public function isApplicable(string $contentType): bool
    {
        return $this->type === $contentType;
    }
}