<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;

class RequestProcessor
{
    public function __construct(
        private readonly AIContentGeneratorInterface $AIContentGenerator,
        private readonly SpecificationHydrator $specificationHydrator
    ) {
    }

    public function execute(array $params): string
    {
        return $this->AIContentGenerator->execute(
            $this->specificationHydrator->hydrate($params)
        );
    }
}