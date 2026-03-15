<?php

namespace App\constants;

class MCP
{

//context format for claude

public const claudeTools =[
[
       "name" => "addMovies",
        "description" => "add an movie info to this list",
        "input_schema" => [
            "type" => "object",
            "properties" => [ "movie_name" => ["type" => "string"], "category" => ["type" => "string"], "ratings" => ["type" => "string"] ],
            "required" => ["movie_name"]
        ]
],
[
       "name" => "getMovieInfo",
        "description" => "show an info for this movie",
        "input_schema" => [
            "type" => "object",
            "properties" => [ "movie_name" => ["type" => "string"],"id" => ["type" => "integer"]],
            "required" => ["movie_name"]
        ]
],
[
       "name" => "getMoviesList",
        "description" => "show an list of movies with description",
        "input_schema" => [
            "type" => "object",
            "properties" => [ ]
        ]
]
];

// context format for gemini mcp

public const GEMINI_TOOLS = [

 "functionDeclarations" => [

  [
   "name" => "addMovies",
   "description" => "Add a movie to the list",
   "parameters" => [
     "type" => "object",
     "properties" => [
        "movie_name" => ["type" => "string"],
        "category" => ["type" => "string"],
        "ratings" => ["type" => "number"]
     ],
     "required" => ["movie_name"]
   ]
  ],

  [
   "name" => "getMovieInfo",
   "description" => "Show info for a movie",
   "parameters" => [
     "type" => "object",
     "properties" => [
        "movie_name" => ["type" => "string"],
        "id" => ["type" => "integer"]
     ],
     "required" => ["movie_name"]
   ]
  ],

  [
   "name" => "getMoviesList",
   "description" => "Return movie list",
   "parameters" => [
     "type" => "object",
     "properties" => [
         "limit" => [
            "type" => "integer"
        ]
     ]
   ]
  ]

 ]
];
}
