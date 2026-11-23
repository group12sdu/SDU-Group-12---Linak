@extends('layouts.app')

@section('title', 'My desk')

@section('content')
    @php
        $mode = match (true) {
            $locked => 'Cleaning mode is on: your desk is raised and locked.',
            $user->desk_height === $user->standing_height => 'Your desk is currently at standing height.',
            $user->desk_height === $user->sitting_height => 'Your desk is currently at sitting height.',
            default => 'Hardware control is not connected.',
        };
    @endphp

    <section id="my-desk-view" class="view">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">Workspace Overview</p>
                <h2>Welcome Back, <span class="current-user-name">{{ $user->name }}</span></h2>
                <p>Manage your desk, personalize your presets, and build healthier habits.</p>
            </div>
            <div class="system-status">
                <span class="status-dot"></span>
                Demo Environment
            </div>
        </div>

        <article>
            <h2>My height presets</h2>
            <div class="card-row">
                @foreach (\App\Models\User::KINDS as $kind)
                    @php $value = $user->preset($kind); @endphp
                    <div class="preset-card" data-preset="{{ $kind }}">
                        <h3>{{ ucfirst($kind) }}</h3>
                        <p class="preset-value">{{ $value === null ? 'Not set' : $value.' cm' }}</p>
                        <p class="current-user-name">{{ $user->name }}</p>

                        <div class="preset-view actions">
                            <form method="POST" action="{{ route('my-desk.use') }}">
                                @csrf
                                <input type="hidden" name="kind" value="{{ $kind }}" />
                                <button type="submit" id="{{ $kind }}-preset" class="primary preset-use"
                                        @disabled($value === null || $locked)
                                        title="{{ $locked ? 'Desks are locked during cleaning mode' : ($value === null ? 'Set a '.$kind.' height first' : '') }}">
                                    Use {{ $kind }} height
                                </button>
                            </form>
                            <button type="button" class="preset-edit">{{ $value === null ? 'Set' : 'Edit' }}</button>
                            @if ($value !== null)
                                <form method="POST" action="{{ route('presets.destroy', $kind) }}"
                                      data-confirm="Delete your {{ $kind }} preset?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger preset-delete">Delete</button>
                                </form>
                            @endif
                        </div>

                        <form class="preset-editor" method="POST" action="{{ route('presets.update', $kind) }}" hidden>
                            @csrf
                            @method('PUT')
                            <label for="{{ $kind }}-input">{{ ucfirst($kind) }} height (cm)</label>
                            <input type="number" id="{{ $kind }}-input" name="height" class="preset-input"
                                   min="{{ \App\Models\User::MIN_HEIGHT }}" max="{{ \App\Models\User::MAX_HEIGHT }}" step="1"
                                   value="{{ $value }}" />
                            <div class="actions">
                                <button type="submit" class="primary">Save</button>
                                <button type="button" class="preset-cancel">Cancel</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="desk-status" role="status" aria-live="polite">
                Current simulated desk height:
                <strong id="current-desk-height">{{ $user->desk_height }} cm</strong>
                <p id="desk-status-message">{{ $mode }}</p>
            </div>
        </article>

        <article>
            <h2>Desks</h2>
            <p>Pick a desk and move it to one of your presets.</p>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Desk ID</th><th>Building</th><th>Floor</th><th>Room</th><th>Desk</th><th>Height</th><th>Move to</th>
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
                                <td>
                                    <span class="desk-height">{{ $desk->height }} cm</span>
                                    <span @class(['desk-mode', 'is-standing' => $desk->mode === 'Standing', 'is-cleaning' => $desk->mode === 'Cleaning'])>{{ $desk->mode }}</span>
                                </td>
                                <td>
                                    @foreach (\App\Models\User::KINDS as $kind)
                                        <form method="POST" action="{{ route('desks.move', $desk) }}">
                                            @csrf
                                            <input type="hidden" name="kind" value="{{ $kind }}" />
                                            <button type="submit" class="desk-{{ $kind }}"
                                                    @disabled($locked || $user->preset($kind) === null)
                                                    title="{{ $locked ? 'Desks are locked during cleaning mode' : ($user->preset($kind) === null ? 'Set a '.$kind.' height first' : '') }}">
                                                {{ ucfirst($kind) }}
                                            </button>
                                        </form>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">No desks yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
