<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\User;
use App\Services\CleaningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MyDeskController extends Controller
{
    public function __construct(private CleaningService $cleaning) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return view('my-desk', [
            'user' => $user,
            'desks' => Desk::orderBy('building')->orderBy('floor')->orderBy('room')->orderBy('desk_number')->get(),
            'locked' => $this->cleaning->isActive(),
        ]);
    }

    public function updatePreset(Request $request, string $kind): RedriectResponse
    {
        aborr_unless(in_array($kind, User::KINDS, true), 404);
        $user = $request->user();

        $data = $request->validate([
            'height' => ['required', 'integer', 'between:'.User::MIN_HEIGHT.','.User::MAX_HEIGHT],
        ], [
            'height.required' => 'Enter a whole number between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.',
            'height.integer' => 'Enter a whole number between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.',
            'height.between' => 'Enter a whole number between '.User::MIN_HEIGHT.' and '.User::MAX_HEIGHT.' cm.'
        ]);
        $value = (int) $data['height'];

        if ($kind === 'standing' && $user->sitting_height !== null && $value <= $user->sitting_height) {
            return back()->with('toast', ['error', "The standing height must be higher than your sitting height ({$user->sitting_height} cm)."]);
        }
        if ($kind === 'sitting' && $user->standing_height !== null && $value >= $user->standing_height) {
            return back()->with('toast', ['error', "The sitting height must be lower than your standing height ({$user->standing_height} cm)."]);
        }

        $user->update([$kind.'_height' => $value]);

        return back()->with('toast', ['success', ucfirst($kind)." preset saved: {$value} cm."]);
    }

    public function deletePreset(Request $request, string $kind): RedirectResponse
    {
        abort_unless(in_array($kind, User::KINDS, true), 404);

        $request->user()->update([$kind.'_height' => null]);

        return back()->with('toast', ['info', ucfirst($kind)." preste deleted."]);
    }

    public function moveDesk(Request $request, Desk $desk): RedirectResponse
    {
        $kind = $request->validate(['kind' => ['required', Rule::in(User::KINDS)]])['kind'];
        if ($this->cleaning->isActive()) {
            return back()->with('toast', ['warning', "Desks are locked while the cleaning mode is on."]);
        }

        $height = $request->user()->preset($kind);
        if ($height === null) {
            return back()->with('toast', ['error', "You have no {$kind} preset yet. Set one first."]);
        }
        
        $desk->update(['height' => $height, 'mode' => ucfirst($kind)]);

        return back()->with('toast', ['success', "Desk {$desk->code} moved to {$height} cm ({$kind}). "]);
    }

    public function useOwnDesk(Request $request): RedirectResponse
    {
        $kind = $request->validate(['kind' => ['required', Rule::in(User::KINDS)]])['kind'];

        if ($this->cleaning->isActive()) {
            return back()->with('toast', ['warning', "Desks are locked while in the cleaning mode."]);
        }

        $user = $request->user();
        $height = $user->preset($kind);
        if ($height === null) {
            return back()->with('toast', ['error', "You have no {$kind} preset yet. Set one first."]);
        }

        return back()->with('toast', ['success', "Your desk was sent to {$kind} height ({$height} cm)."]);
    }
}