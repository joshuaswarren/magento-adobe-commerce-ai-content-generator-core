<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api\Data;

interface PromptUserMsgProviderInterface
{
    public function execute(SpecificationInterface $specification): string;
}