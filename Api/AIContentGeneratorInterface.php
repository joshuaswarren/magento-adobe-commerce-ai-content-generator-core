<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Api;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\ContentGeneratorNotFoundException;
use Creatuity\AIContent\Exception\UnsupportedContentTypeException;
use Magento\Framework\Exception\LocalizedException;

interface AIContentGeneratorInterface
{
    /**
     * @param SpecificationInterface $specification
     * @return AIResponseInterface
     * @throws ContentGeneratorNotFoundException
     * @throws UnsupportedContentTypeException
     * @throws LocalizedException
     */
    public function execute(SpecificationInterface $specification): AIResponseInterface;
}