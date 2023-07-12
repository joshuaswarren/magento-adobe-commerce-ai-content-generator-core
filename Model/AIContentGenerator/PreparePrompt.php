<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Attribute\AttributesDataProviderInterface;

class PreparePrompt
{
    public function __construct(
        private readonly AttributesDataProviderInterface $attributesDataProvider,
    ) {
    }

    public function generate(SpecificationInterface $specification, string $template): string
    {
        $prompt = (string) __(
            $template,
            [
                'type' => $specification->getContentType(),
                'max_len' => $specification->getMaxLength(),
                'min_len' => $specification->getMinLength()
            ]
        );
        $prompt .= "\n\n" . __('Product attributes:') . "\n";
        $prompt .= $this->attributesDataProvider->execute($specification);

        return $prompt;
    }
}