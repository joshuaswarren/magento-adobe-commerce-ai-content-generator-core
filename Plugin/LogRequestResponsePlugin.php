<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Plugin;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Model\Config\AiContentGeneralConfig;
use Psr\Log\LoggerInterface;

class LogRequestResponsePlugin
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly AiContentGeneralConfig $aiContentGeneralConfig
    ) {
    }

    public function aroundCall(
        AIProviderInterface $subject,
        callable $proceed,
        AIRequestInterface $request,
        SpecificationInterface $specification
    ): AIResponseInterface {
        /** @var AIResponseInterface $result */
        $result = $proceed($request, $specification);

        if (!$this->aiContentGeneralConfig->isDebugEnabled()) {
            return $result;
        }

        $this->logger->debug(
            '[AI Provider Request]',
            [
                'request' => $request->getData(),
                'specification' => $specification->getData(),
                'response' => $result->getChoices()
            ]
        );

        return $result;
    }
}