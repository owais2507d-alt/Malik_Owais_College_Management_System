<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-status';

    protected $description = 'Sync event statuses with their schedules: upcoming, ongoing, completed';

    public function handle(): int
    {
        $today = Carbon::today();
        $now = Carbon::now()->toTimeString();

        $completed = Event::query()
            ->where('status', '!=', Event::STATUS_COMPLETED)
            ->whereDate('event_date', '<', $today)
            ->update(['status' => Event::STATUS_COMPLETED]);

        $completed += Event::query()
            ->whereIn('status', [Event::STATUS_UPCOMING, Event::STATUS_ONGOING])
            ->whereDate('event_date', $today)
            ->where('end_time', '<', $now)
            ->update(['status' => Event::STATUS_COMPLETED]);

        $ongoing = Event::query()
            ->where('status', Event::STATUS_UPCOMING)
            ->whereDate('event_date', $today)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => Event::STATUS_ONGOING]);

        $reset = Event::query()
            ->where('status', Event::STATUS_ONGOING)
            ->whereDate('event_date', '>', $today)
            ->update(['status' => Event::STATUS_UPCOMING]);

        $this->info(sprintf(
            'Event statuses synced: %d completed, %d ongoing, %d reset to upcoming.',
            $completed,
            $ongoing,
            $reset,
        ));

        return self::SUCCESS;
    }
}
