@extends('layouts.app')

@section('title', 'Manage employees')

@section('content')
    @php
        $isEditing = $editing !== null;
        $action = $isEditing ? route('admin.employees.update', $editing) : route('admin.employees.store');
        $val = fn ($field) => old($field, $editing?->{$field});
    @endphp

    <section id="admin-employees-view" class="view">
        <article>
            <h2>{{ $isEditing ? 'Edit employee' : 'Add an employee' }}</h2>
            <form method="POST" action="{{ $action }}">
                @csrf
                @if ($isEditing) @method('PUT') @endif

                <label for="employee-name">Name</label>
                <input type="text" id="employee-name" name="name" placeholder="Employee name" value="{{ $val('name') }}" required />

                <label for="employee-username">Username</label>
                <input type="text" id="employee-username" name="username" placeholder="Username" value="{{ $val('username') }}" required />

                <label for="employee-password">Password{{ $isEditing ? ' (leave empty to keep the current one)' : '' }}</label>
                <input type="password" id="employee-password" name="password" autocomplete="new-password"
                       placeholder="Password" @required(! $isEditing) />

                <label for="employee-role">Role</label>
                <select id="employee-role" name="role">
                    <option value="employee" @selected($val('role') !== 'admin')>Employee</option>
                    <option value="admin" @selected($val('role') === 'admin')>Admin</option>
                </select>

                <label for="employee-standing">Standing height (cm, optional)</label>
                <input type="number" id="employee-standing" name="standing_height" value="{{ $val('standing_height') }}"
                       min="{{ \App\Models\User::MIN_HEIGHT }}" max="{{ \App\Models\User::MAX_HEIGHT }}" step="1" />

                <label for="employee-sitting">Sitting height (cm, optional)</label>
                <input type="number" id="employee-sitting" name="sitting_height" value="{{ $val('sitting_height') }}"
                       min="{{ \App\Models\User::MIN_HEIGHT }}" max="{{ \App\Models\User::MAX_HEIGHT }}" step="1" />

                <div class="actions">
                    <button type="submit" class="primary">{{ $isEditing ? 'Save changes' : 'Add employee' }}</button>
                    @if ($isEditing)
                        <a href="{{ route('admin.employees.index') }}"><button type="button">Cancel editing</button></a>
                    @else
                        <button type="reset">Cancel</button>
                    @endif
                </div>
            </form>
        </article>

        <article>
            <h2>All employees</h2>
            <div class="table-wrap">
                <table id="employee-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Username</th><th>Role</th><th>Standing</th><th>Sitting</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td class="emp-name">{{ $employee->name }}</td>
                                <td class="emp-username">{{ $employee->username }}</td>
                                <td class="emp-role">{{ ucfirst($employee->role) }}</td>
                                <td class="emp-standing">{{ $employee->standing_height === null ? 'Not set' : $employee->standing_height.' cm' }}</td>
                                <td class="emp-sitting">{{ $employee->sitting_height === null ? 'Not set' : $employee->sitting_height.' cm' }}</td>
                                <td>
                                    <a href="{{ route('admin.employees.index', ['edit' => $employee->id]) }}"><button type="button">Edit</button></a>
                                    @unless ($employee->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                                              data-confirm="Delete {{ $employee->name }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="danger">Delete</button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
