<?php

namespace App\Services;

use App\Models\CleaningSetting;
use App\Models\Desk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleaningService
{
    public function isActive(): bool
    {
        return CleaningSetting::current()->active;
    }

    /** Raise every desk to the cleaning height and lock the app. Returns the number of desks raised. */
    public function start(): ?int
    {
        $setting = CleaningSetting::current();
        if ($setting->active) {
            return null; // already on
        }

        $target = $setting->targetHeight();

        return DB::transaction(function () use ($setting, $target) {
            // Remember what everything was, so we can restore it afterwards.
            Desk::query()->update([
                'previous_height' => DB::raw('height'),
                'previous_mode' => DB::raw('mode'),
            ]);
            Desk::query()->update(['height' => $target, 'mode' => 'Cleaning']);

            User::query()->update(['previous_desk_height' => DB::raw('desk_height')]);
            User::query()->update(['desk_height' => $target]);

            $setting->update(['active' => true]);

            return Desk::count();
        });
    }

    /** Put everything back. Returns false if cleaning mode was not on. */
    public function end(): bool
    {
        $setting = CleaningSetting::current();
        if (! $setting->active) {
            return false;
        }

        DB::transaction(function () use ($setting) {
            Desk::query()->whereNotNull('previous_height')->update([
                'height' => DB::raw('previous_height'),
                'mode' => DB::raw('COALESCE(previous_mode, mode)'),
                'previous_height' => null,
                'previous_mode' => null,
            ]);
            // Desks added during cleaning have no snapshot - they just go back to the default.
            Desk::query()->where('mode', 'Cleaning')->update(['height' => 72, 'mode' => 'Sitting']);

            User::query()->whereNotNull('previous_desk_height')->update([
                'desk_height' => DB::raw('previous_desk_height'),
                'previous_desk_height' => null,
            ]);

            $setting->update(['active' => false]);
        });

        return true;
    }

    /** Called every minute by the scheduler. Returns true if cleaning mode was started. */
    public function runScheduled(): bool
    {
        $setting = CleaningSetting::current();
        if (! $setting->enabled || $setting->active) {
            return false;
        }

        $now = now();
        $alreadyRanToday = $setting->last_run_date?->isSameDay($now) ?? false;

        if ($now->format('H:i') !== $setting->time || $alreadyRanToday) {
            return false;
        }

        $setting->update(['last_run_date' => $now->toDateString()]);

        return $this->start() !== null;
    }
}
