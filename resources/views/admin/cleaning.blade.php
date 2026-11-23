@extends('layouts.app')

@section('title', 'Cleaning')

@section('content')
    <section id="admin-cleaning-view" class="view">
        <article>
            <h2>Cleaning preset</h2>
            <p>At the chosen time every desk rises to the cleaning height, so the cleaning staff can reach the floor.</p>

            <form id="cleaning-form" method="POST" action="{{ route('admin.cleaning.update') }}">
                @csrf
                @method('PUT')

                <div class="check-row">
                    <input type="checkbox" id="cleaning-enabled" name="enabled" value="1" @checked(old('enabled', $setting->enabled)) />
                    <label for="cleaning-enabled">Start cleaning mode automatically every day</label>
                </div>

                <label for="cleaning-time">Cleaning staff begins at</label>
                <input type="time" id="cleaning-time" name="time" value="{{ old('time', $setting->time) }}" />

                <div class="check-row">
                    <input type="checkbox" id="cleaning-use-max" name="use_max" value="1" @checked(old('use_max', $setting->use_max)) />
                    <label for="cleaning-use-max">Raise each desk to its maximum height</label>
                </div>

                <label for="cleaning-height">Or a fixed cleaning height (cm)</label>
                <input type="number" id="cleaning-height" name="height" value="{{ old('height', $setting->height) }}"
                       min="{{ \App\Models\User::MIN_HEIGHT }}" max="{{ \App\Models\User::MAX_HEIGHT }}" />

                <button type="submit" class="primary">Save preset</button>
            </form>
        </article>

        <article>
            <h2>Cleaning mode</h2>
            <p>Status: <strong id="cleaning-status" @class(['status-active' => $setting->active])>{{ $setting->active ? 'Active' : 'Inactive' }}</strong></p>
            <div class="actions">
                <form method="POST" action="{{ route('admin.cleaning.start') }}">
                    @csrf
                    <button type="submit" id="start-cleaning" class="primary" @disabled($setting->active)>Start cleaning mode now</button>
                </form>
                <form method="POST" action="{{ route('admin.cleaning.end') }}">
                    @csrf
                    <button type="submit" id="end-cleaning" @disabled(! $setting->active)>End cleaning mode (restore heights)</button>
                </form>
            </div>
        </article>
    </section>
@endsection
