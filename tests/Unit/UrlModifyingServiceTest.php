<?php

namespace Tests\Unit;

use App\Services\UrlModifyingService;
use PHPUnit\Framework\TestCase;

class UrlModifyingServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testUrlModifyingServiceHashAndIdGeneration(): void
    {
        $urlModifyingService = new UrlModifyingService();

        $ids = range(1100, 1200, 5);

        foreach ($ids as $id) {
            $hash = $urlModifyingService->idToHash($id);
            $generatedId = $urlModifyingService->hashToId($hash);

            $this->assertTrue($generatedId === $id);
        }
    }
}
