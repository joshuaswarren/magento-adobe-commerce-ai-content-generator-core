<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config;

class GetDescriptionAttributes
{
    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig
    ) {
    }

    public function execute(?int $storeId = null): array
    {
        return $this->aiContentGeneralConfig->getDescriptionAttributes($storeId);
    }
}