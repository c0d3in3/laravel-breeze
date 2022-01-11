<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/countries/list', [CountryController::class, 'getCountries'])->middleware('auth:sanctum');
Route::get('/countries/visited', [CountryController::class, 'getVisitedCountries'])->middleware('auth:sanctum');
Route::get('/countries/tovisit', [CountryController::class, 'getCountriesToVisit'])->middleware('auth:sanctum');
Route::post('/countries/add-visited-country', [CountryController::class, 'addVisitedCountry'])->middleware('auth:sanctum');
Route::post('/countries/add-country-to-visit', [CountryController::class, 'addCountryToVisit'])->middleware('auth:sanctum');
Route::delete('/countries/delete-visited-country', [CountryController::class, 'deleteVisitedCountry'])->middleware('auth:sanctum');
Route::delete('/countries/delete-country-to-visit', [CountryController::class, 'deleteCountryToVisit'])->middleware('auth:sanctum');

