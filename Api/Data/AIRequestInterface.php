<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface AIRequestInterface
{
    public const INPUT_FIELD = 'input';

    /**
     * @return string
     */
    public function getInput(): string;

    /**
     * @return mixed[]
     */
    public function getData();

    /**
     * @param string $input
     * @return void
     */
    public function setInput(string $input): void;
}