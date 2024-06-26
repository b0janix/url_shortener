<?php

namespace App\Services;

use App\Services\Interfaces\UrlModifyingInterface;

class UrlModifyingService implements UrlModifyingInterface
{
    const BASE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const BASE_LENGTH = 63;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function idToHash(int $id): string
    {
        $remainder = $id % self::BASE_LENGTH;
        $hash = self::BASE[$remainder];
        $q = floor($id/self::BASE_LENGTH);
        while ($q) {
            $remainder = $q % self::BASE_LENGTH;
            $q =floor($q/self::BASE_LENGTH);
            $hash = self::BASE[$remainder].$hash;
        }
        return $hash;
    }

    public function hashToId(string $hash): int
    {
        $limit = strlen($hash);
        $res = strpos(self::BASE, $hash[0]);
        for($i = 1; $i < $limit; $i++) {
            $res = self::BASE_LENGTH * $res + strpos(self::BASE, $hash[$i]);
        }
        return $res;
    }
}
