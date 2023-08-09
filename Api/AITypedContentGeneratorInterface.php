<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api;

interface AITypedContentGeneratorInterface extends AIContentGeneratorInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $contentType
     * @return bool
     */
    public function isApplicable(string $contentType): bool;
}