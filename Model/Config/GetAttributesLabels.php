<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Model\Config;

use Creatuity\AIContent\Exception\NoDescriptionAttributesChosenException;
use Creatuity\AIContent\Model\Attribute\GetAttributeLabels;

class GetAttributesLabels
{
    public function __construct(
        private readonly GetAttributeLabels $getAttributeLabels
    ) {
    }

    /**
     * @return array<string, string>
     * @throws NoDescriptionAttributesChosenException
     */
    public function get(array $attrCodes): array
    {
        if (empty($attrCodes)) {
            throw new NoDescriptionAttributesChosenException(__('No description attributes found'));
        }

        return $this->getAttributeLabels->execute($attrCodes);
    }
}