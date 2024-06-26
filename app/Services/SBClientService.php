<?php

namespace App\Services;

use App\Enums\PlatformTypeEnum;
use App\Enums\ThreatEntryTypeEnum;
use App\Enums\ThreatTypeEnum;
use App\Services\Interfaces\SBClientInterface;
use Illuminate\Support\Facades\Http;

class SBClientService implements SBClientInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

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
        ]
    ): mixed
    {
       return Http::contentType('application/json')
            ->accept('application/json')
            ->send(
            'POST',
            'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . config('app.sb_api_key'),
            [
                'client'     => [
                    'clientId'      => config('app.sb_client_id'),
                    'clientVersion' => config('app.sb_client_version'),
                ],
                'threatInfo' => [
                    'threatTypes'      => $threatTypes,
                    'platformTypes'    => $platformTypes,
                    'threatEntryTypes' => $threatEntryTypes,
                    'threatEntries'    => array_map(function ($url) {
                        return ['url' => $url];
                    }, $urls),
                ],
            ]
        )->json();
    }
}
