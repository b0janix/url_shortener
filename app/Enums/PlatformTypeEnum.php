<?php

namespace App\Enums;

enum PlatformTypeEnum: string
{
    case PLATFORM_TYPE_UNSPECIFIED = 'PLATFORM_TYPE_UNSPECIFIED';
    case WINDOWS = 'WINDOWS';
    case LINUX = 'LINUX';
    case ANDROID = 'ANDROID';
    case OSX = 'OSX';
    case IOS = 'IOS';
    case ANY_PLATFORM = 'ANY_PLATFORM';
    case ALL_PLATFORMS = 'ALL_PLATFORMS';
    case CHROME = 'CHROME';
}
