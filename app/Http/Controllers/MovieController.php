<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $MAX_BANNER = 3;
        $MAX_MOVIE_ITEM = 10;
        $MAX_TV_SHOW_ITEM = 10;

        // Hit API Banner
        $bannerResponse = Http::get("{$baseURL}/trending/movie/week",[
            'api_key' => $apiKey,
        ]);

        // Prepare variable
        $bannerArray = [];

        //Check API response
        if ($bannerResponse->successful()){
            // Check data is null or not
            $resultArray = $bannerResponse->object()->results;

            if (isset($resultArray)){
                // Looping response data
                foreach ($resultArray as $item){
                    //save response data to new variable
                    array_push($bannerArray, $item);

                    // Max 3 items
                    if (count($bannerArray) == $MAX_BANNER) {
                        break;
                    }
                }
            }
        }

        // Hit API Top 10 Movies
        $topMoviesResponse = Http::get("{$baseURL}/movie/top_rated",[
            'api_key' => $apiKey,
        ]);

        // Prepare variable
        $topMoviesArray = [];

        //check API Response
        if ($topMoviesResponse->successful()){
            //check data is null or not
            $resultArray = $topMoviesResponse->object()->results;
            if (isset($resultArray)){
                //looping response data
                foreach ($resultArray as $item){
                    //save response data to new variable
                    array_push($topMoviesArray, $item);

                    //max 10 items
                    if (count($topMoviesArray) == $MAX_MOVIE_ITEM){
                        break;
                    }
                }
            }
        }

        // Hit API Top 10 TV Show
        $topTVShowResponse = Http::get("{$baseURL}/tv/top_rated",[
            'api_key' => $apiKey,
        ]);

        // Prepare variable
        $topTVShowArray = [];

        //check API Response
        if ($topTVShowResponse->successful()){
            //check data is null or not
            $resultArray = $topTVShowResponse->object()->results;
            if (isset($resultArray)){
                //looping response data
                foreach ($resultArray as $item){
                    //save response data to new variable
                    array_push($topTVShowArray, $item);

                    //max 10 items
                    if (count($topTVShowArray) == $MAX_TV_SHOW_ITEM){
                        break;
                    }
                }
            }
        }

        return view('home', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'banner' => $bannerArray,
            'topMovies' => $topMoviesArray,
            'topTVShows' => $topTVShowArray
        ]);
    }

    public function movies(){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        $movieResponse = Http::get("{$baseURL}/discover/movie", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $movieArray = [];

        //Check API response
        if ($movieResponse->successful()){
            // Check data is null or not
            $resultArray = $movieResponse->object()->results;

            if (isset($resultArray)){
                // Looping response data
                foreach ($resultArray as $item){
                    //save response data to new variable
                    array_push($movieArray, $item);

                }
            }
        }

        return view('movie', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movies' => $movieArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter
        ]);
    }

    public function tvShow(){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.desc";
        $page = 1;
        $minimalVoter = 100;

        $tvResponse = Http::get("{$baseURL}/discover/tv", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vote_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $tvArray = [];

        //Check API response
        if ($tvResponse->successful()){
            // Check data is null or not
            $resultArray = $tvResponse->object()->results;

            if (isset($resultArray)){
                // Looping response data
                foreach ($resultArray as $item){
                    //save response data to new variable
                    array_push($tvArray, $item);

                }
            }
        }

        return view('tv', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'tvShow' => $tvArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVoter' => $minimalVoter
        ]);
    }

    public function search(){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        return view('search', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
        ]);
    }

    public function movieDetails($id){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseURL}/movie/{$id}", [
            'api_key' => $apiKey,
            'append_to_response' => 'videos'
        ]);

        $moviedata = null;

        if ($response->successful()){
            $movieData = $response->object();
        }

        return view('movie_details', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'movieData' => $movieData
        ]);
    }

    public function tvDetails($id){
        $baseURL = env('MOVIE_DB_BASE_URL');
        $imageBaseURL = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseURL}/tv/{$id}", [
            'api_key' => $apiKey,
            'append_to_response' => 'videos'
        ]);

        $tvData = null;

        if ($response->successful()){
            $tvData = $response->object();
        }

        return view('tv_details', [
            'baseURL' => $baseURL,
            'imageBaseURL' => $imageBaseURL,
            'apiKey' => $apiKey,
            'tvData' => $tvData
        ]);
    }
}
