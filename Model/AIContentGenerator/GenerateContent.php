<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Model\GetAIProvider;
use Creatuity\AIContent\Api\Data\AIRequestInterfaceFactory;

class GenerateContent
{
    public function __construct(
        private readonly GetAIProvider $provider,
        private readonly AIRequestInterfaceFactory $AIRequestInterfaceFactory
    ) {
    }

    public function execute(string $prompt, ?int $storeId = null): string
    {
        $provider = $this->provider->execute($storeId);
        /** @var AIRequestInterface $apiRequest */
        $apiRequest = $this->AIRequestInterfaceFactory->create(['data' => ['input' => $prompt]]);

        return $provider->call($apiRequest)->getContent();
    }
}