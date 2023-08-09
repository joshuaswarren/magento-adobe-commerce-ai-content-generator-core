<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Attribute;

use Creatuity\AIContent\Api\Data\PromptUserMsgProviderInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;

interface AttributesDataProviderInterface extends PromptUserMsgProviderInterface
{
    public function execute(SpecificationInterface $specification): string;
}