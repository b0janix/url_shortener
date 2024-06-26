<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'domain' => $this->domain,
            'long_url' => $this->long_url,
            'short_url' => $this->short_url,
            'full_short_url' => 'https://' . $this->domain . '/' . $this->short_url,
            'full_long_url' => 'https://' . $this->domain . '/' . $this->long_url,
        ];
    }
}
