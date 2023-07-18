<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config;

class GetMetaTagsAttributes
{
    public function __construct(
        private readonly AiContentGeneralConfig $aiContentGeneralConfig
    ) {
    }

    public function execute(?int $storeId = null): array
    {
        return $this->aiContentGeneralConfig->getMetaAttributes($storeId);
    }
}