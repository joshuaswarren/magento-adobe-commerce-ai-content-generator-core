<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ContentType;

interface AIFormContentTypeProviderInterface
{
    public function get(): ?string;
}