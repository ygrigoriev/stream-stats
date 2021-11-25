<?php
declare(strict_types=1);

use App\Http\Controllers\Auth\Oauth\TwitchController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$router->get('/', function (Illuminate\Http\Request $request) {
    return redirect()->route($request->user() ? 'dashboard.index' : 'oauth.twitch.login');
});

$router->group(['prefix' => '/dashboard', 'as' => 'dashboard.'/*, 'middleware' => 'auth'*/], function () use ($router) {
    $router->get('/', [DashboardController::class, 'index'])->name('index');
});

$router->group(['namespace' => 'Auth\Oauth', 'prefix' => '/oauth', 'as' => 'oauth.'], function () use ($router) {
    $router->group(['prefix' => '/twitch', 'as' => 'twitch.'], function () use ($router) {
        $router->get('login', [TwitchController::class, 'login'])->name('login');
        $router->get('callback', [TwitchController::class, 'callback']);
    });
});

$router->get('/signout', [SignOutController::class, 'index'])->name('signout');
