<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Enum;

enum AiContentTypeEnum: string
{
    const DESCRIPTION_TYPE = 'description';
    const META_KEYWORDS_TYPE = 'meta-keywords';
    const META_TITLE_TYPE = 'meta-title';
    const META_DESCRIPTION_TYPE = 'meta-description';
    const SHORT_DESCRIPTION_TYPE = 'short_description';

    case DESCRIPTION_GROUP = 'descriptions';
    case META_GROUP = 'meta_tags';
    case DESCRIPTION = self::DESCRIPTION_TYPE;
    case SHOR_DESCRIPTION = self::SHORT_DESCRIPTION_TYPE;
    case META_KEYWORDS = self::META_KEYWORDS_TYPE;
    case META_TITLE = self::META_TITLE_TYPE;
    case META_DESCRIPTION = self::META_DESCRIPTION_TYPE;
}
