<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Dingo\Api\Routing\Router;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BookmarkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->resource('companies', CompanyController::class);
    
    $api->group(['prefix' => 'auth'], function(Router $api) {    
        $api->post('signup', [SignUpController::class, 'signUp']); 
        $api->post('login', [LoginController::class, 'login']); 
        $api->group(['middleware' => ['api.auth'], 'providers' => ['api-key']], function(Router $api) {            
            $api->post('auth', [LoginController::class, 'auth']);             
        });
    });
    
    $api->group(['middleware' => 'jwt.auth', 'providers' => ['jwt']], function(Router $api) {
        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
        
        $api->group(['prefix' => 'auth'], function(Router $api) {   
            $api->get('me', [LoginController::class, 'me']);       
            $api->post('logout', [LoginController::class, 'logout']);            
        });
        
        $api->match(['delete'], 'bookmarks/batch', [BookmarkController::class, 'batchDestroy']);
        $api->match(['put'], 'bookmarks/orders', [BookmarkController::class, 'batchUpdateOrders']);
        $api->resource('bookmarks', BookmarkController::class);        
    });
});