<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ContentType;

use Magento\Framework\App\RequestInterface;

class AIFormContentTypeFromRequest implements AIFormContentTypeProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request
    ) {
    }

    public function get(): ?string
    {
        return $this->request->getQuery('ai_content_group')
            ?: $this->request->getParam('ai_content_group') ?: null;
    }
}