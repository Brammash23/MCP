<?php

namespace App\Http\Controllers;

use App\constants\MCP;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;


class MoviesController extends Controller
{
    public function getMovieInfo(Request $request)
    {
        $id = $request->id ?? null;
        $movieName = $request->movie_name ?? null;
        $movieInfo = DB::table('movies')
        ->when(!empty($id),function ($q) use($id) {
            $q->where('id',$id);
        })
        ->when(!empty($movieName),function ($q) use($movieName) {
            $q->where('movie_name',$movieName);
        })
        ->where('is_deleted',0)
        ->first();

        return response()->json([
            'status'=>200,
            'movie_info'=>$movieInfo
        ],200);    
    }

    public function getMoviesList(Request $request) : JsonResponse{
        $movieInfo = DB::table('movies')
        ->select('movie_name','category','released_year','ratings')
        ->where('is_deleted',0)
        ->get();

    
     return response()->json([
            'status'=>200,
            'message' => 'Success',
            'movie_info'=>$movieInfo
        ],200);    
    }

    public function addMovies(Request $request)
    {
        $data=[
            'movie_name' => $request->movie_name,
            'released_year' => $request->released_year ?? Carbon::now(),
            'category' => $request->category,
            'ratings' => $request->ratings
        ];
        $movieId= DB::table('movies')->insertGetId($data);
          return response()->json([
            'status'=>200,
            'message' => 'Added',
            'movie_id'=> $movieId
        ],200);    


    }

    public function UpdateMovies(Request $request)
    {
        $id = $request->id ?? null;
        $data=[
            'movie_name' => $request->movie_name,
            'released_year' => $request->released_year ?? Carbon::now(),
            'category' => $request->category,
            'ratings' => $request->ratings
        ];
        DB::table('movies')
        ->when(!empty($id),function ($q) use($id) {
            $q->where('id',$id);
        })->update($data);
          return response()->json([
            'status'=>200,
            'message' => 'Updated'
        ],200);    


    }

    
    public function DeleteMovie(Request $request)
    {
        $id = $request->id ?? null;
        $data=[
            'is_deleted' => 1
        ];
        DB::table('movies')
        ->when(!empty($id),function ($q) use($id) {
            $q->where('id',$id);
        })->update($data);
          return response()->json([
            'status'=>200,
            'message' => 'Updated'
        ],200);    


    }

    public function AssistMe(Request $request)
    {

        $prompt = $request->prompt;
        return $this->chat($prompt);


    }

     public function chat($prompt)
    {
       //forms an request based on model now gemini passes the params prompt and tools to the exceuter
    
     $model = config('gemini.model');

        $url = config('gemini.endpoint')
            . "/{$model}:generateContent?key="
            . config('gemini.api_key');

        $response = Http::post($url, [

            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],

        "tools" => [MCP::GEMINI_TOOLS]

        ]);

        $toolName = data_get($response, 'candidates.0.content.parts.0.functionCall.name');
        $args = data_get($response, 'candidates.0.content.parts.0.functionCall.args');
        return $this->tool_exceuter($toolName,$args);
        //     $response = Http::get(
        //     "https://generativelanguage.googleapis.com/v1beta/models",
        //     ["key" => config('gemini.api_key')]
        // );

        // list which model is currently available
    }

    public function tool_exceuter($toolName,$args){

    //based on the return repsonse from prompt it returns the args and which one need to exceute 
        $args =  new Request($args);
        return match ($toolName) {

            'addMovies' => self::addMovies($args),

            'getMovieInfo' => self::getMovieInfo($args),

            'getMoviesList' => self::getMoviesList($args),

            default => [
                'error' => 'Unknown tool'
            ]
        };

        
    }
}
