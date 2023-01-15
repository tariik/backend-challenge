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
    
    /**
    * Get the specified number of posts from the external API
    *
    * @param integer $posts The number of posts to retrieve
    * @return array The array of posts retrieved
    * @throws \Exception if there is an error getting the posts from the external API
    */
    public function getPosts(int $posts): array
    {
        // Make a GET request to the endpoint/posts and store the response
        try {
            $response = Http::get($this->endpoit.'/posts');
            $statusCode = $response->status();
            if($statusCode === 200){
                return array_slice($response->json(), 0, $posts);
            }else{
                //If the status code is different than 200, log the error and throw an exception
                Log::error("Error getting posts from external API. Status code: $statusCode");
                throw new \Exception("Error getting posts from external API");
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
    * Get the users from the external API
    *
    * @return array
    * @throws \Exception
    */
    public function getUsers(): array
    {
        //Make a GET request to the endpoint/users and store the response
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