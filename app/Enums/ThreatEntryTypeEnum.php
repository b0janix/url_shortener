<?php

namespace App\Enums;

enum ThreatEntryTypeEnum: string
{
    case THREAT_ENTRY_TYPE_UNSPECIFIED = 'THREAT_ENTRY_TYPE_UNSPECIFIED';
    case URL = 'URL';
    case EXECUTABLE = 'EXECUTABLE';
}
