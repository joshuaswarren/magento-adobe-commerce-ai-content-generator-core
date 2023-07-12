<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Magento\Framework\App\RequestInterface;

class IsProductCreatePage
{
    public function __construct(
        private readonly RequestInterface $request
    ) {
    }

    public function execute(): bool
    {
        return $this->request->getFullActionName() === 'catalog_product_new';
    }
}