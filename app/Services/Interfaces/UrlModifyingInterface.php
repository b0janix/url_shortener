<?php

namespace App\Services\Interfaces;

interface UrlModifyingInterface
{
    public function idToHash(int $id): string;

    public function hashToId(string $hash): int;
}
