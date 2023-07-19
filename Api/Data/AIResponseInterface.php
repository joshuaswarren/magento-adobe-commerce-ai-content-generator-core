<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface AIResponseInterface
{
    public const CHOICES_FIELD = 'choices';

    /**
     * @return string[]
     */
    public function getChoices(): array;

    /**
     * @param string[] $choices
     * @return void
     */
    public function setChoices(string $choices): void;
}