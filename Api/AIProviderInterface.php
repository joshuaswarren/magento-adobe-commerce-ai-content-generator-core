<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;

interface AIProviderInterface
{
    /**
     * @param AIRequestInterface $request
     * @param SpecificationInterface $specification
     * @return AIResponseInterface
     * @throws \Creatuity\AIContentOpenAI\Exception\OpenAiModelHandlerNotFoundException
     * @throws \Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function call(AIRequestInterface $request, SpecificationInterface $specification): AIResponseInterface;

    /**
     * @return bool
     */
    public function isApplicable(string $name): bool;
}