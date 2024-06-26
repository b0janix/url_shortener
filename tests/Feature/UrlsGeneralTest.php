<?php

namespace Feature;

use App\Models\Url;
use App\Services\UrlModifyingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlsGeneralTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testGettingInitialData(): void
    {
        $urlModifyingService = new UrlModifyingService();

        foreach (range(2000, 2010) as $i) {
            Url::factory()->create(['short_url' => $urlModifyingService->idToHash($i)]);
        }

        $response = $this->get('/api/urls');

        $response->assertStatus(200);

        $resArray = $response->json();

        $this->assertArrayHasKey('data', $resArray);
        $this->assertArrayHasKey('meta', $resArray);
        $this->assertArrayHasKey('links', $resArray);
        $this->assertCount(5, $resArray['data']);
        $this->assertEquals(11, $resArray['meta']['total']);
    }

    public function testStoreUrlSuccessfully(): void
    {
        $response = $this->postJson(
            '/api/urls/store', [
                'url' => 'https://www.google.com/search?q=football&sca_esv=94e45fce1d51b060&sxsrf=ADLYWII24JOUlEBWh9l8umVqLQONnBjnBg%3A1719360602692&source=hp&ei=Wlx7Zv_tJ8CJ7NYP3vqM6As&iflsig=AL9hbdgAAAAAZntqaqaqqA4AelPHVHbaBvowNPc4Cx1K&ved=0ahUKEwj_gtmv_feGAxXABNsEHV49A70Q4dUDCBY&uact=5&oq=football&gs_lp=Egdnd3Mtd2l6Ighmb290YmFsbDIKECMYgAQYJxiKBTIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQLhiABEiuDFAAWL4JcAB4AJABAJgBeqABugaqAQMzLjW4AQPIAQD4AQGYAgigAq4HwgIEECMYJ8ICCxAuGIAEGNEDGMcBwgILEAAYgAQYkQIYigXCAgsQLhiABBjHARivAcICCBAAGIAEGMkDmAMAkgcDMC44oAehTA&sclient=gws-wiz'
            ]
        );

        $response->assertStatus(201);

        $resArray = $response->json();

        $this->assertArrayHasKey('data', $resArray);
        $this->assertEquals(12, $resArray['data']['id']);
        $this->assertArrayHasKey('message', $resArray);
        $this->assertEquals('Url created', $resArray['message']);
    }

    public function testStoreUrlValidationError(): void
    {
        $response = $this->postJson(
            '/api/urls/store',
        );

        $response->assertStatus(422);

        $resArray = $response->json();

        $this->assertArrayHasKey('message', $resArray);
        $this->assertEquals('The url field is required.', $resArray['message']);
    }

    public function testStoreUrlSameUrlError(): void
    {
        $this->postJson(
            '/api/urls/store', [
                'url' => 'https://www.google.com/search?q=football&sca_esv=94e45fce1d51b060&sxsrf=ADLYWII24JOUlEBWh9l8umVqLQONnBjnBg%3A1719360602692&source=hp&ei=Wlx7Zv_tJ8CJ7NYP3vqM6As&iflsig=AL9hbdgAAAAAZntqaqaqqA4AelPHVHbaBvowNPc4Cx1K&ved=0ahUKEwj_gtmv_feGAxXABNsEHV49A70Q4dUDCBY&uact=5&oq=football&gs_lp=Egdnd3Mtd2l6Ighmb290YmFsbDIKECMYgAQYJxiKBTIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQLhiABEiuDFAAWL4JcAB4AJABAJgBeqABugaqAQMzLjW4AQPIAQD4AQGYAgigAq4HwgIEECMYJ8ICCxAuGIAEGNEDGMcBwgILEAAYgAQYkQIYigXCAgsQLhiABBjHARivAcICCBAAGIAEGMkDmAMAkgcDMC44oAehTA&sclient=gws-wiz'
            ]
        );

        $response = $this->postJson(
            '/api/urls/store', [
                'url' => 'https://www.google.com/search?q=football&sca_esv=94e45fce1d51b060&sxsrf=ADLYWII24JOUlEBWh9l8umVqLQONnBjnBg%3A1719360602692&source=hp&ei=Wlx7Zv_tJ8CJ7NYP3vqM6As&iflsig=AL9hbdgAAAAAZntqaqaqqA4AelPHVHbaBvowNPc4Cx1K&ved=0ahUKEwj_gtmv_feGAxXABNsEHV49A70Q4dUDCBY&uact=5&oq=football&gs_lp=Egdnd3Mtd2l6Ighmb290YmFsbDIKECMYgAQYJxiKBTIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQLhiABEiuDFAAWL4JcAB4AJABAJgBeqABugaqAQMzLjW4AQPIAQD4AQGYAgigAq4HwgIEECMYJ8ICCxAuGIAEGNEDGMcBwgILEAAYgAQYkQIYigXCAgsQLhiABBjHARivAcICCBAAGIAEGMkDmAMAkgcDMC44oAehTA&sclient=gws-wiz'
            ]
        );

        $response->assertStatus(409);

        $resArray = $response->json();

        $this->assertArrayHasKey('message', $resArray);
        $this->assertEquals('Url not created for the following reasons: This url is already inserted', $resArray['message']);
    }

    public function testRedirect(): void
    {
        $this->postJson(
            '/api/urls/store', [
                'url' => 'https://www.google.com/search?q=football&sca_esv=94e45fce1d51b060&sxsrf=ADLYWII24JOUlEBWh9l8umVqLQONnBjnBg%3A1719360602692&source=hp&ei=Wlx7Zv_tJ8CJ7NYP3vqM6As&iflsig=AL9hbdgAAAAAZntqaqaqqA4AelPHVHbaBvowNPc4Cx1K&ved=0ahUKEwj_gtmv_feGAxXABNsEHV49A70Q4dUDCBY&uact=5&oq=football&gs_lp=Egdnd3Mtd2l6Ighmb290YmFsbDIKECMYgAQYJxiKBTIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQLhiABEiuDFAAWL4JcAB4AJABAJgBeqABugaqAQMzLjW4AQPIAQD4AQGYAgigAq4HwgIEECMYJ8ICCxAuGIAEGNEDGMcBwgILEAAYgAQYkQIYigXCAgsQLhiABBjHARivAcICCBAAGIAEGMkDmAMAkgcDMC44oAehTA&sclient=gws-wiz'
            ]
        );

        $this->get('/api/urls/e')->assertRedirect('https://google.com/search?q=football&sca_esv=94e45fce1d51b060&sxsrf=ADLYWII24JOUlEBWh9l8umVqLQONnBjnBg%3A1719360602692&source=hp&ei=Wlx7Zv_tJ8CJ7NYP3vqM6As&iflsig=AL9hbdgAAAAAZntqaqaqqA4AelPHVHbaBvowNPc4Cx1K&ved=0ahUKEwj_gtmv_feGAxXABNsEHV49A70Q4dUDCBY&uact=5&oq=football&gs_lp=Egdnd3Mtd2l6Ighmb290YmFsbDIKECMYgAQYJxiKBTIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMgUQLhiABEiuDFAAWL4JcAB4AJABAJgBeqABugaqAQMzLjW4AQPIAQD4AQGYAgigAq4HwgIEECMYJ8ICCxAuGIAEGNEDGMcBwgILEAAYgAQYkQIYigXCAgsQLhiABBjHARivAcICCBAAGIAEGMkDmAMAkgcDMC44oAehTA&sclient=gws-wiz');
    }


}
