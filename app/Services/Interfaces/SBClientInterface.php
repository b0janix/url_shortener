<?php

namespace App\Services\Interfaces;

use App\Enums\PlatformTypeEnum;
use App\Enums\ThreatEntryTypeEnum;
use App\Enums\ThreatTypeEnum;

interface SBClientInterface
{
    public function lookUp(
        array $urls,
        array $threatTypes = [
            ThreatTypeEnum::MALWARE->value,
            ThreatTypeEnum::SOCIAL_ENGINEERING->value,
            ThreatTypeEnum::POTENTIALLY_HARMFUL_APPLICATION->value,
            ThreatTypeEnum::THREAT_TYPE_UNSPECIFIED->value,
            ThreatTypeEnum::UNWANTED_SOFTWARE->value,
        ],
        array $platformTypes = [
            PlatformTypeEnum::ANY_PLATFORM->value,
        ],
        array $threatEntryTypes = [
            ThreatEntryTypeEnum::URL->value,
            ThreatEntryTypeEnum::EXECUTABLE->value,
            ThreatEntryTypeEnum::THREAT_ENTRY_TYPE_UNSPECIFIED->value,
        ]): mixed;
}
