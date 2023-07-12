<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface AIResponseInterface
{
    public const CONTENT_FIELD = 'content';

    public function getContent(): string;
}