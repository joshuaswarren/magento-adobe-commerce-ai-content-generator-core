<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Exception\AIProviderNotFoundException;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;

class GetAIProvider
{
    /**
     * @param AiContentGeneralConfig $aiContentGeneralConfig
     * @param AIProviderInterface[] $providers
     */
    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig,
        private readonly array $providers = []
    ) {
    }

    /**
     * @throws AIProviderNotFoundException
     */
    public function execute(?int $storeId = null): AIProviderInterface
    {
        $providerName = $this->aiContentGeneralConfig->getAIProvider($storeId);
        foreach ($this->providers as $provider) {
            if ($provider instanceof AIProviderInterface && $provider->isApplicable($providerName)) {
                return $provider;
            }
        }

        throw new AIProviderNotFoundException(__('%1 provider is not supported', $providerName));
    }
}