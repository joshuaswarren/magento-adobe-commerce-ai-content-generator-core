<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterfaceFactory;

class RequestProcessor
{
    public function __construct(
        private readonly SpecificationInterfaceFactory $specificationFactory,
        private readonly AIContentGeneratorInterface $AIContentGenerator
    ) {
    }

    public function execute(array $params): string
    {
        /** @var \Creatuity\AIContent\Api\Data\SpecificationInterface $spec */
        $spec = $this->specificationFactory->create(['data' => $params]);

        return $this->AIContentGenerator->execute($spec);
    }
}