<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PopulationTime;
use App\Models\Stream;
use App\Models\StreamTag;
use App\Services\Twitch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Total number of streams for each game
     *
     * @return View
     */
    public function task_1(): View
    {
        $streams = DB::table('streams')
            ->selectRaw('game_name, COUNT(*) AS streams_count')
            ->groupBy('game_name')
            ->orderBy('streams_count', 'desc')
            ->get();

        return $this->index(['data' => $streams]);
    }

    /**
     * Top games by viewer count for each game
     *
     * @return View
     */
    public function task_2(): View
    {
        $streams = DB::table('streams')
            ->selectRaw('game_name, SUM(viewer_count) AS viewer_count')
            ->groupBy('game_name')
            ->orderBy('viewer_count', 'desc')
            ->get();

        return $this->index(['data' => $streams]);
    }

    /**
     * Median number of viewers for all streams
     *
     * @return View
     */
    public function task_3(): View
    {
        $streams = DB::table('streams')
            ->selectRaw('AVG(viewer_count) "average_viewer_count"')
            ->get();

        return $this->index(['data' => $streams->first()->average_viewer_count]);
    }

    /**
     * List of top 100 streams by viewer count that can be sorted asc & desc
     *
     * @param string $order
     * @return View
     */
    public function task_4($order): View
    {
        $streams = DB::table('streams')
            ->select('*')
            ->orderBy('viewer_count', 'desc')
            ->limit(100)
            ->get();

        if ($order === 'asc') {
            $streams = $streams->reverse();
        }

        return $this->index(['data' => $streams]);
    }

    /**
     * Total number of streams by their start time (rounded to the nearest hour)
     *
     * @return View
     */
    public function task_5(): View
    {
        $streams = Stream::all();
        $streamGroups = $streams->mapToGroups(function ($stream) {
            $rounded = Carbon::create(
                $stream->started_at->year,
                $stream->started_at->month,
                $stream->started_at->day,
                $stream->started_at->minute < 30 ? $stream->started_at->hour : $stream->started_at->hour + 1
            );
            return [$rounded->rawFormat(\DateTimeInterface::W3C) => $stream->started_at->toRfc2822String()];
        })->sortKeys();

        return $this->index(['data' => $streamGroups]);
    }

    /**
     * Which of the top 1000 streams is the logged in user following?
     *
     * @param Twitch $twitch
     * @return View
     */
    public function task_6(Twitch $twitch): View
    {
        $topStreams = Stream::all()->keyBy('id');
        $followedStreams = $twitch->getFollowedStreams();
        $intersect = $topStreams->intersectByKeys(array_column($followedStreams, 'id', 'id'));

        return $this->index(['data' => $intersect]);
    }

    /**
     * How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?
     *
     * @param Twitch $twitch
     * @return View
     */
    public function task_7(Twitch $twitch): View
    {
        $topStreamsMinimalViewrs = Stream::select()->min('viewer_count');

        $followedStreams = $twitch->getFollowedStreams();
        $followedStreamsMinimalViewrs = array_reduce($followedStreams, function ($carry, $item) {
            if ($carry === null || $item->viewer_count < $carry) {
                $carry = $item->viewer_count;
            }
            return $carry;
        });

        return $this->index(['data' => $topStreamsMinimalViewrs - $followedStreamsMinimalViewrs]);
    }

    /**
     * Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?
     *
     * @param Twitch $twitch
     * @return View
     */
    public function task_8(Twitch $twitch): View
    {
        $followedStreams = $twitch->getFollowedStreams();

        $topStreamTags = StreamTag::selectRaw('DISTINCT(tag_id)')->get();
        $followedStreamTags = [];
        foreach ($followedStreams as $followedStream) {
            $followedStreamTags = array_merge($followedStreamTags, $followedStream->tag_ids);
        }

        $sharedStreamTags = $topStreamTags->pluck('tag_id')->intersect($followedStreamTags)->unique();
        $tagsData = $twitch->getStreamTags($sharedStreamTags->toArray());

        return $this->index(['data' => array_column($tagsData, null, 'tag_id')]);
    }

    private function index(array $data = []): View
    {
        return view('dashboard', [
            'user' => $this->request->user(),
            'data' => $data,
            'currentRoute' => $this->request->route()->getName(),
            'routes' => $this->getRoutes(),
            'populationTime' => PopulationTime::query()->latest('updated_at')->first(),
        ]);
    }

    private function getRoutes()
    {
        return [
            [
                'name' => 'dashboard.task_1',
                'title' => '1. Total number of streams for each game',
            ], [
                'name' => 'dashboard.task_2',
                'title' => '2. Top games by viewer count for each game',
            ], [
                'name' => 'dashboard.task_3',
                'title' => '3. Median number of viewers for all streams',
            ], [
                'name' => 'dashboard.task_4',
                'title' => '4. List of top 100 streams by viewer count that can be sorted asc & desc',
                'params' => ['desc'],
            ], [
                'name' => 'dashboard.task_5',
                'title' => '5. Total number of streams by their start time (rounded to the nearest hour)'
            ], [
                'name' => 'dashboard.task_6',
                'title' => '6. Which of the top 1000 streams is the logged in user following?'
            ], [
                'name' => 'dashboard.task_7',
                'title' => '7. How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?'
            ], [
                'name' => 'dashboard.task_8',
                'title' => '8. Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?'
            ],
        ];
    }
}
