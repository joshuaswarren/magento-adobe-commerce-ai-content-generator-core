<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\DataProvider\ProductId;

use Magento\Framework\App\RequestInterface;

class AIFormProductIdFromRequest implements AIFormProductIdProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request
    ) {
    }

    public function get(): ?int
    {
        $productId = (int)$this->request->getParam('product_id');

        return $productId ?: null;
    }
}