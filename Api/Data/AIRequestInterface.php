<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface AIRequestInterface
{
    public const PROMPT = 'prompt';

    /**
     * @return PromptInterface[]
     */
    public function getPrompt(): array;

    /**
     * @param string $param
     * @param mixed $value
     * @return void
     */
    public function setParam(string $param, mixed $value): void;

    /**
     * @param string $param
     * @return mixed
     */
    public function getParam(string $param): mixed;

    /**
     * @return mixed
     */
    public function getParams(): mixed;


    /**
     * @param PromptInterface[] $prompt
     * @return void
     */
    public function setPrompt(array $prompt): void;
}