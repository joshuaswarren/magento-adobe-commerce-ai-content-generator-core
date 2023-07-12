<?php

declare(strict_types=1);

namespace Creatuity\AIContent\Enum;

enum AiContentTypeEnum: string
{
    case DESCRIPTION_GROUP = 'descriptions';
    case META_GROUP = 'meta_tags';
    case DESCRIPTION = 'description';
    case SHOR_DESCRIPTION = 'short_description';
    case META_KEYWORDS = 'meta-keywords';
    case META_TITLE = 'meta-title';
    case META_DESCRIPTION = 'meta-description';
}
