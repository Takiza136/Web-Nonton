<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YouTubeController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = env('YOUTUBE_API_KEY');
    }

    /**
     * Display the homepage
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Search for YouTube videos
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $maxResults = $request->input('maxResults', 12);
        $regionCode = $request->input('regionCode', 'ID');

        try {
            $response = Http::get("{$this->baseUrl}/search", [
                'key' => $this->apiKey,
                'part' => 'snippet',
                'q' => $query,
                'type' => 'video',
                'videoEmbeddable' => 'true', // Ensure videos are embeddable
                'maxResults' => $maxResults,
                'regionCode' => $regionCode, // Filter by region
                'relevanceLanguage' => 'id', // Prefer Indonesian content
                'order' => 'relevance'
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => 'Failed to fetch videos'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trending/popular videos
     */
    public function trending(Request $request)
    {
        $maxResults = $request->input('maxResults', 12);
        $regionCode = $request->input('regionCode', 'ID');

        try {
            // Fetch more results than needed to account for non-embeddable videos
            $fetchLimit = 50;
            
            $response = Http::get("{$this->baseUrl}/videos", [
                'key' => $this->apiKey,
                'part' => 'snippet,statistics,status', // Include status to check embeddable
                'chart' => 'mostPopular',
                'regionCode' => $regionCode,
                'maxResults' => $fetchLimit
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Filter only embeddable videos
                if (isset($data['items'])) {
                    $data['items'] = collect($data['items'])
                        ->filter(function ($item) {
                            return isset($item['status']['embeddable']) && $item['status']['embeddable'];
                        })
                        ->take($maxResults)
                        ->values()
                        ->all();
                }

                return response()->json($data);
            }

            return response()->json([
                'error' => 'Failed to fetch trending videos'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video details by ID
     */
    public function videoDetails($videoId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/videos", [
                'key' => $this->apiKey,
                'part' => 'snippet,statistics,contentDetails',
                'id' => $videoId
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => 'Failed to fetch video details'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
