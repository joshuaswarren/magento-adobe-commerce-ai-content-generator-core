<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;

interface AIProviderInterface
{
    /**
     * @param AIRequestInterface $request
     * @return AIResponseInterface
     * @throws \Creatuity\AIContentOpenAI\Exception\OpenAiModelHandlerNotFoundException
     * @throws \Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException
     */
    public function call(AIRequestInterface $request): AIResponseInterface;

    /**
     * @return bool
     */
    public function isApplicable(string $name): bool;
}