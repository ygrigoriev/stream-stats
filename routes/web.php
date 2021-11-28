<?php
declare(strict_types=1);

use App\Http\Controllers\Auth\Oauth\TwitchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignOutController;

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
    return $request->user() ? redirect()->route('dashboard.task_1') : view('signin');
})->name('index');

$router->group(['namespace' => 'Auth\Oauth', 'prefix' => '/oauth', 'as' => 'oauth.'], function () use ($router) {
    $router->group(['prefix' => '/twitch', 'as' => 'twitch.'], function () use ($router) {
        $router->get('login', [TwitchController::class, 'login'])->name('login');
        $router->get('callback', [TwitchController::class, 'callback']);
    });
});

$router->get('/signout', [SignOutController::class, 'index'])->name('signout');
$router->get('/noaccess', function (Illuminate\Http\Request $request) {
    return view('noaccess');
})->name('noaccess');

$router->group(['prefix' => '/dashboard', 'as' => 'dashboard.', 'middleware' => 'auth'], function () use ($router) {
    $router->get('task_1', [DashboardController::class, 'task_1'])->name('task_1');
    $router->get('task_2', [DashboardController::class, 'task_2'])->name('task_2');
    $router->get('task_3', [DashboardController::class, 'task_3'])->name('task_3');
    $router->get('task_4/{order}', [DashboardController::class, 'task_4'])->name('task_4');
    $router->get('task_5', [DashboardController::class, 'task_5'])->name('task_5');
    $router->get('task_6', [DashboardController::class, 'task_6'])->name('task_6');
    $router->get('task_7', [DashboardController::class, 'task_7'])->name('task_7');
    $router->get('task_8', [DashboardController::class, 'task_8'])->name('task_8');
});
