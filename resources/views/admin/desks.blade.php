@extends('layouts.app')

@section('title', 'Manage desks')

@section('content')
    <section id="admin-desks-view" class="view">
        <article>
            <h2>Add a desk</h2>
            <form class="inline-form" method="POST" action="{{ route('admin.desks.store') }}">
                @csrf
                <label for="desk-building">Building</label>
                <input type="number" id="desk-building" name="building" min="1" value="{{ old('building', 1) }}" />
                <label for="desk-floor">Floor</label>
                <input type="number" id="desk-floor" name="floor" min="0" value="{{ old('floor', 1) }}" />
                <label for="desk-room">Room</label>
                <input type="number" id="desk-room" name="room" min="1" value="{{ old('room', 101) }}" />
                <label for="desk-number">Desk in room</label>
                <input type="number" id="desk-number" name="desk_number" min="1" value="{{ old('desk_number', 1) }}" />
                <button type="submit" class="primary">Add desk</button>
            </form>
        </article>

        <article>
            <h2>All desks</h2>
            @if ($locked)
                <p class="hint">Cleaning mode is on, so desk heights are locked.</p>
            @endif
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Desk ID</th><th>Building</th><th>Floor</th><th>Room</th><th>Desk</th><th>Height</th><th>Set height</th><th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($desks as $desk)
                            <tr data-desk-id="{{ $desk->code }}">
                                <td>{{ $desk->code }}</td>
                                <td>{{ $desk->building }}</td>
                                <td>{{ $desk->floor }}</td>
                                <td>{{ $desk->room }}</td>
                                <td>{{ $desk->desk_number }}</td>
                                <td class="admin-desk-height">{{ $desk->height }} cm</td>
                                <td>
                                    <form class="admin-height-form" method="POST" action="{{ route('admin.desks.update', $desk) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="height" class="admin-height-input" data-autosubmit
                                               value="{{ $desk->height }}"
                                               min="{{ \App\Models\User::MIN_HEIGHT }}" max="{{ \App\Models\User::MAX_HEIGHT }}"
                                               aria-label="Set height for desk {{ $desk->code }}" @disabled($locked) />
                                        <button type="submit" @disabled($locked)>Set</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.desks.destroy', $desk) }}"
                                          data-confirm="Remove desk {{ $desk->code }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger admin-desk-remove">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8">No desks yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
