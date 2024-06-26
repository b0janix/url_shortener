<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Http\Requests\UpdateUrlRequest;
use App\Http\Resources\UrlCollection;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Services\Interfaces\SBClientInterface;
use App\Services\Interfaces\UrlModifyingInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UrlController extends Controller
{
    const HASH_LENGTH_LIMIT = 6;
    public function __construct(
        private UrlModifyingInterface $url,
        private SBClientInterface $sbClient
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): UrlCollection
    {
       return new UrlCollection(DB::table('urls')->orderBy('id', 'desc')->paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlRequest $request): JsonResponse
    {
        try {
            $fullUrl = $request->input('url');

            $sbResponse = $this->sbClient->lookUp([$fullUrl]);

            if (empty($sbResponse)) {
                $domain = parse_url($fullUrl)['host'];
                $longUrl = explode($domain, $fullUrl)[1];
                $domain = preg_replace('/^www\./i', '', $domain);


                DB::beginTransaction();

                if (Url::where(['domain' => $domain, 'long_url' => $longUrl])->exists()) {
                    throw new \Exception('This url is already inserted', 409);
                }

                $url = Url::query()->create(['long_url' => $longUrl, 'domain' => $domain]);

                $hash = $this->url->idToHash($url->id);

                if(strlen($hash) > self::HASH_LENGTH_LIMIT) {
                    throw new \Exception('The generated hash is too long.', 413);
                }

                $url->update(['short_url' => $hash]);

                DB::commit();

                return response()->json(['message' => 'Url created', 'data' => new UrlResource($url)], 201);
            }

            return response()->json(['message' => 'The url is not safe for browsing.'], 400);

        } catch (\Throwable $e) {

            $code = (int) $e->getCode();


             if($code < 400 || $code > 599) {
                 $code = 500;
             }

            DB::rollBack();

            return response()->json(['message' => 'Url not created for the following reasons: ' . $e->getMessage()], $code);
        }
    }

    /**
     * Display the specified resource.
     */
    public function redirectToOriginal(string $hash): RedirectResponse|JsonResponse
    {
        try {
            $url = Url::findOrFail($this->url->hashToId($hash));

            $longUrl = 'https://' . $url->domain . $url->long_url;

            return redirect()->away($longUrl, 301);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'There was some kind of an error: ' . $e->getMessage()]);
        }
    }
}
