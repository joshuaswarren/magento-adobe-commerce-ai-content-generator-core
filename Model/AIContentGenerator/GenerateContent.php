<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\PromptInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\GetAIProvider;
use Creatuity\AIContent\Api\Data\AIRequestInterfaceFactory;

class GenerateContent
{
    public function __construct(
        private readonly GetAIProvider $provider,
        private readonly AIRequestInterfaceFactory $AIRequestInterfaceFactory
    ) {
    }

    /**
     * @param PromptInterface[] $prompt
     * @param SpecificationInterface $specification
     * @return AIResponseInterface
     * @throws \Creatuity\AIContentOpenAI\Exception\OpenAiModelHandlerNotFoundException
     * @throws \Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException
     * @throws \Creatuity\AIContent\Exception\AIProviderNotFoundException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(array $prompt, SpecificationInterface $specification): AIResponseInterface
    {
        $provider = $this->provider->execute($specification->getStoreId());
        /** @var AIRequestInterface $apiRequest */
        $apiRequest = $this->AIRequestInterfaceFactory->create(['data' => ['prompt' => $prompt]]);

        return $provider->call($apiRequest, $specification);
    }
}