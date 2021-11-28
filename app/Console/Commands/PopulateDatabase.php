<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates Database with Twitch data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(\App\Services\Twitch $twitch): int
    {
        $topStreams = $twitch->getStreams();
        $streamTags = array_column($topStreams, 'tag_ids', 'id');

        $streamRows = array_map(function (\stdClass $class) {
            return [
                'id' => $class->id,
                'user_name' => $class->user_name,
                'title' => $class->title,
                'game_name' => $class->game_name,
                'viewer_count' => $class->viewer_count,
                'started_at' => new \DateTimeImmutable($class->started_at)
            ];
        }, $topStreams);

        $tagRows = [];
        foreach ($streamTags as $streamId => $tagIds) {
            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $tagRows[] = ['stream_id' => $streamId, 'tag_id' => $tagId];
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('streams')->truncate();
        DB::table('stream_tags')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        try {
            DB::beginTransaction();
            DB::table('streams')->insertOrIgnore($streamRows);
            DB::table('stream_tags')->insertOrIgnore($tagRows);
            DB::table('population_time')->insert(['updated_at' => new \DateTimeImmutable()]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
