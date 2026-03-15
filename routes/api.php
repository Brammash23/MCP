<?php

use App\Http\Controllers\MoviesController;
use Illuminate\Support\Facades\Route;

Route::controller(MoviesController::class)->group(function () {
    Route::get('/movies_list', 'getMoviesList')->name('movies_list');
    Route::get('/movies_info', 'getMovieInfo');
    Route::post('/add_movies', 'addMovies');
    Route::post('/assist_me', 'AssistMe');
});
