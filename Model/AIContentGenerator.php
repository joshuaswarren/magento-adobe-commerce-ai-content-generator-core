<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model;

use Creatuity\AIContent\Api\AIContentGeneratorInterface;
use Creatuity\AIContent\Api\AITypedContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\ContentGeneratorNotFoundException;
use Magento\Framework\Exception\LocalizedException;

class AIContentGenerator implements AIContentGeneratorInterface
{
    /**
     * @param AITypedContentGeneratorInterface[] $typedGenerators
     */
    public function __construct(
        private readonly array $typedGenerators = []
    ) {
    }

    public function execute(SpecificationInterface $specification): AIResponseInterface
    {
        foreach ($this->typedGenerators as $generator) {
            if (!$generator instanceof AITypedContentGeneratorInterface) {
                throw new LocalizedException(
                    __(
                        'Generator %1 is not instance of %2',
                        get_class($generator),
                        AITypedContentGeneratorInterface::class
                    )
                );
            }

            if ($generator->isApplicable($specification->getContentType())) {
                return $generator->execute($specification);
            }
        }

        throw new ContentGeneratorNotFoundException(
            __('Failed to generate content due to lack of "%1" generator', $specification->getContentType())
        );
    }
}