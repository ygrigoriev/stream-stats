<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth\Oauth;

use App\Models\User;
use App\Models\UserTwitch;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Two\User as ServiceUser;

class TwitchController extends BaseController
{
    public function __construct(Request $request, AuthManager $auth, private Factory $socialiteManager)
    {
        parent::__construct($request, $auth);
    }

    protected function getProvider(): \SocialiteProviders\Twitch\Provider
    {
        return $this->socialiteManager
            ->with('twitch')
            ->scopes(['user:read:follows'])
            ->stateless();
    }

    protected function persistUser(ServiceUser $twitchUser): User
    {
        $user = null;
        DB::transaction(function () use (&$user, $twitchUser) {
            $user = $this->request->user();
            if (!$user) {
                $user = User::firstOrCreate([
                    'name' => $twitchUser->getName(),
                    'email' => $twitchUser->getEmail(),
                    'image_url' => $twitchUser->getAvatar(),
                ]);
            }
            UserTwitch::updateOrCreate([
                'id' => $twitchUser->getId(),
            ], [
                'id' => $twitchUser->getId(),
                'user_id' => $user->id,
                'name' => $twitchUser->getName(),
                'email' => $twitchUser->getEmail(),
                'avatar' => $twitchUser->getAvatar(),
                'token' => $twitchUser->token,
                'refresh_token' => $twitchUser->refreshToken,
                'expires_in' => $twitchUser->expiresIn,
            ]);
        });

        return $user;
    }
}
