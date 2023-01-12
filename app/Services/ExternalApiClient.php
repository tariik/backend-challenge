<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiClient
{
    private $endpoit;

    public function __construct()
    {
        $this->endpoit = env('ENDPOINT');
    }
    
    public function getPosts($posts): array
    {
        
        try {
            $response = Http::get($this->endpoit.'/posts');
            $statusCode = $response->status();
            if($statusCode === 200){
                return array_slice($response->json(), 0, $posts);
            }else{
                Log::error("Error getting posts from external API. Status code: $statusCode");
                throw new \Exception("Error getting posts from external API");
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getUsers(): array
    {
        
        try {
            $response = Http::get($this->endpoit.'/users');
            $statusCode = $response->status();
            if($statusCode === 200){
                return $response->json();
            }else{
                Log::error("Error getting posts from external API. Status code: $statusCode");
                throw new \Exception("Error getting posts from external API");
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}