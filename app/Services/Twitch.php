<?php

namespace App\Services;

use App\Models\UserTwitch;
use Symfony\Component\HttpFoundation\Response;

class Twitch
{
    private const DEFAULT_STREAMS_LIMIT = 1000;

    public function __construct(private \romanzipp\Twitch\Twitch $driver, private \Illuminate\Auth\AuthManager $auth)
    {
        if ($auth->check()) {
            $driver->withToken($auth->user()->userTwitch->token);
        }
    }

    /**
     * Retrieve authenticated user twitch followed streams
     *
     * @return \stdClass[]
     */
    public function getFollowedStreams(): array
    {
        $getFollowedStreamsImpl = function (\romanzipp\Twitch\Result $result = null, $items = []) use (&$getFollowedStreamsImpl) {
            $result = $this->driver->getFollowedStreams(['user_id' => $this->auth->user()->userTwitch->id], $result?->next());
            if ($result->getStatus() === Response::HTTP_UNAUTHORIZED) {
                $this->refreshToken();
                return $getFollowedStreamsImpl();
            }

            $items = array_merge($items, $result->data());

            if ($result->hasMoreResults()) {
                return $getFollowedStreamsImpl($result, $items);
            }

            return $items;
        };

        return $getFollowedStreamsImpl();
    }

    /**
     * Retrieve top streams
     *
     * @return \stdClass[]
     */
    public function getStreams(int $limit = self::DEFAULT_STREAMS_LIMIT): array
    {
        $getStreamsImpl = function (\romanzipp\Twitch\Result $result = null, $items = []) use (&$getStreamsImpl, $limit) {
            $result = $this->driver->getStreams(['first' => 100], $result?->next());

            $items = array_merge($items, $result->data());

            if ($result->hasMoreResults() && count($items) < $limit) {
                return $getStreamsImpl($result, $items);
            }

            return array_slice($items, 0, $limit);
        };

        return $getStreamsImpl();
    }

    /**
     * Retrieve streams tags by tag ids
     *
     * @return \stdClass[]
     */
    public function getStreamTags(array $tagIds): array
    {
        // A bug in the lib :-P
        // There is a method \romanzipp\Twitch\Concerns\Api\TagsTrait::getAllStreamTags which calls streams/tags instead of tags/streams
        // Filed an issue https://github.com/romanzipp/Laravel-Twitch/issues/111
        return $this->driver->get('tags/streams', ['first' => 100, 'tag_id' => $tagIds])->data();
    }

    /**
     * Refresh twitch access token when it expires
     */
    private function refreshToken(): void
    {
        $data = $this->driver->getOAuthToken(
            $this->auth->user()->userTwitch->refresh_token,
            \romanzipp\Twitch\Enums\GrantType::REFRESH_TOKEN,
            ['user:read:email', 'user:read:follows']);

        $this->driver->withToken($data->data()->access_token);

        UserTwitch::updateOrCreate([
            'id' => $this->auth->user()->userTwitch->id,
        ], [
            'token' => $data->data()->access_token,
            'refresh_token' => $data->data()->refresh_token,
        ]);
    }
}
