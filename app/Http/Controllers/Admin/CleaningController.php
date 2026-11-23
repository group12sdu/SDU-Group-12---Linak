<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningSetting;
use App\Models\User;
use App\Services\CleaningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CleaningController extends Controller
{
    public function __construct(private CleaningService $cleaning) {}

    public function index()
    {
        return view('admin.cleaning', ['setting' => CleaningSetting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $useMax = $request->boolean('use_max');

        $data = $request->validate([
            'time' => ['required', 'date_format:H:i'],
            // The height field is disabled (not sent) while "use max" is ticked.
            'height' => [Rule::requiredIf(! $useMax), 'nullable', 'integer', 'between:'.User::MIN_HEIGHT.','.User::MAX_HEIGHT],
        ], [
            'time.required' => 'Choose the time the cleaning staff begins.',
            'time.date_format' => 'Choose the time the cleaning staff begins.',
            'height.required' => 'Enter a cleaning height between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.',
            'height.integer' => 'Enter a cleaning height between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.',
            'height.between' => 'Enter a cleaning height between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.',
        ]);

        $setting = CleaningSetting::current();
        $changes = [
            'enabled' => $request->boolean('enabled'),
            'time' => $data['time'],
            'use_max' => $useMax,
        ];
        if (! $useMax) {
            $changes['height'] = (int) $data['height'];
        }
        if ($data['time'] !== $setting->time) {
            $changes['last_run_date'] = null; // new time can run again today
        }
        $setting->update($changes);

        $how = $setting->use_max ? 'their maximum height' : $setting->height.' cm';
        $message = $setting->enabled
            ? "Cleaning preset saved: every day at {$setting->time} all desks rise to {$how}."
            : 'Cleaning preset saved. Automatic start is off.';

        return back()->with('toast', ['success', $message]);
    }

    public function start(): RedirectResponse
    {
        $count = $this->cleaning->start();
        if ($count === null) {
            return back()->with('toast', ['info', 'Cleaning mode is already on.']);
        }

        $target = CleaningSetting::current()->targetHeight();

        return back()->with('toast', ['warning', "Cleaning mode is ON: {$count} desks raised to {$target} cm and locked."]);
    }

    public function end(): RedirectResponse
    {
        if (! $this->cleaning->end()) {
            return back()->with('toast', ['info', 'Cleaning mode is not on.']);
        }

        return back()->with('toast', ['success', 'Cleaning mode ended: desks are back at their previous heights.']);
    }
}