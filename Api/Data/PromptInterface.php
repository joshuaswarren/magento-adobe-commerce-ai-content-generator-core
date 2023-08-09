<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface PromptInterface extends \Stringable
{
    /**
     * @return string[]
     */
    public function getInput(): array;
}