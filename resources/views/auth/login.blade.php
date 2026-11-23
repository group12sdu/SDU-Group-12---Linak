@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <section id="login-view" class="view">
        <h2>Log in</h2>

        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <label for="login-username">Username</label>
            <input type="text" id="login-username" name="username" value="{{ old('username') }}"
                   autocomplete="username" placeholder="Enter Username" required autofocus />

            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password"
                   autocomplete="current-password" placeholder="Enter Password" required />

            <button type="submit" class="primary">Log In</button>
        </form>
    </section>
@endsection
