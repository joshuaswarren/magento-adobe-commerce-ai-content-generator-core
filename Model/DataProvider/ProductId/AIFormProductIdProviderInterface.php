<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ProductId;

interface AIFormProductIdProviderInterface
{
    public function get(): ?int;
}