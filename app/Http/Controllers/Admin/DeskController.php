<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desk;
use App\Models\User;
use App\Services\CleaningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeskController extends Controller
{
    public function __construct(private CleaningService $cleaning) {}

    public function index()
    {
        return view('admin.desks', [
            'desks' => Desk::orderBy('building')->orderBy('floor')->orderBy('room')->orderBy('desk_number')->get(),
            'locked' => $this->cleaning->isActive()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'building' => ['required', 'integer', 'min:1'],
            'floor' => ['required', 'integer', 'min:0'],
            'room' => ['required', 'integer', 'min:1'],
            'desk_number' => ['required', 'integer', 'min:1', Rule::unique('desks')->where(
                fn ($q) => $q->where('building', $request->input('building'))
                    ->where('floor', $request->input('floor'))
                    ->where('room', $request->input('room'))
            )]
        ], [
            'desk_number.unique' => "That desk already exists."
        ]);

        $desk = Desk::create($data + ['height' => 72, 'mode' => 'Sitting']);

        return back()->with('toast',['success', "Desk {$desk->code} added."]);
    }

    public function updateHeight(Request $request, Desk $desk): RedirectResponse
    {
        if ($this->cleaning->isActive()) {
            return back()->with('toast', ['warning', "Desks are locked while the cleaning mode is on."]);
        }

        $validator = validator($request->all(), [
            'height' => ['required', 'integer', 'between'.User::MIN_HEIGHT.','.User::MAX_HEIGHT]
        ]);
        if ($validator->fails()) {
            return back()->with('toast', ['error', "Desk {$desk->code}: enter a whole number between ".User::MIN_HEIGHT." and ".User::MAX_HEIGHT." cm."]);
        }

        $height = (int) $request->input('height');
        if ($height !== $desk->height) {
            $desk->update(['height' => $height, 'mode' => 'Custom']);
            return back()->with('toast', ['success', "Desk {$desk->code} height set to {$height} cm."]);
        }
    }

    public function destroy(Desk $desk): RedirectResponse
    {
        $code = $desk->code;
        $desk->delete();

        return back()->with('toast', ['warning', "Desk {$code} removed."]);
    }
}