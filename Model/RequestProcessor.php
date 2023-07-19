<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;

class RequestProcessor
{
    public function __construct(
        private readonly AIContentGeneratorInterface $AIContentGenerator,
        private readonly SpecificationHydrator $specificationHydrator
    ) {
    }

    public function execute(array $params): AIResponseInterface
    {
        return $this->AIContentGenerator->execute(
            $this->specificationHydrator->hydrate($params)
        );
    }
}