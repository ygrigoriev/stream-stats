<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth\Oauth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;

abstract class BaseController extends Controller
{
    public function __construct(Request $request, private AuthManager $auth)
    {
        parent::__construct($request);
    }

    abstract protected function getProvider(): AbstractProvider;

    public function login(): RedirectResponse
    {
        return $this->getProvider()->redirect();
    }

    public function callback()
    {
        $currentUser = $this->request->user();
        try {
            $serviceUser = $this->getProvider()->user();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return redirect()->route('noaccess');
        }
        $user = $this->persistUser($serviceUser);
        if (!$currentUser) {
            $this->auth->guard()->login($user, true);
        }

        return redirect()->route('dashboard.task_1');
    }
}
