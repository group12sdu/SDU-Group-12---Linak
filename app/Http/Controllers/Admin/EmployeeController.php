<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.employees', [
            'employees' => User::orderBy('id')->get(),
            'editing' => $request->filled('edit') ? User::find($request->query('edit')) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, passwordRequired: true);
        $user = User::create($data);

        return redirect()->route('admin.employees.index')
            ->with('toast', ['success', "Employee {$user->name} added."]);
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        $data = $this->validated($request, passwordRequired: false, ignore: $employee);

        if (empty($data['password'])) {
            unset($data['password']); // keep the old one
        }
        // Don't let an admin demote themselves out of the app.
        if ($employee->is($request->user()) && ($data['role'] ?? 'admin') !== 'admin') {
            return back()->with('toast', ['error', 'You cannot remove your own admin role.']);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('toast', ['success', "Employee {$employee->name} updated."]);
    }

    public function destroy(Request $request, User $employee): RedirectResponse
    {
        if ($employee->is($request->user())) {
            return back()->with('toast', ['error', 'You cannot delete your own account.']);
        }

        $name = $employee->name;
        $employee->delete();

        return back()->with('toast', ['warning', "Employee {$name} deleted."]);
    }

    private function validated(Request $request, bool $passwordRequired, ?User $ignore = null): array
    {
        $height = ['nullable', 'integer', 'between:'.User::MIN_HEIGHT.','.User::MAX_HEIGHT];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($ignore)],
            'password' => [$passwordRequired ? 'required' : 'nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['employee', 'admin'])],
            'standing_height' => $height,
            'sitting_height' => $height,
        ]);

        $data['username'] = strtolower(trim($data['username']));

        $standing = $data['standing_height'] ?? null;
        $sitting = $data['sitting_height'] ?? null;
        if ($standing !== null && $sitting !== null && (int) $standing <= (int) $sitting) {
            abort(back()->withInput()->with('toast', ['error', 'The standing height must be higher than the sitting height.']));
        }

        return $data;
    }
}