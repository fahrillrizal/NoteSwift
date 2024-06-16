@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input readonly type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

    <h2>Change Password</h2>
    <form method="POST" action="{{ route('profile.updatePassword') }}">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>

    <h2>Link/Unlink Accounts</h2>
    @if($user->google_id)
        <form method="POST" action="{{ route('profile.unlinkProvider', 'google') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Unlink Google Account</button>
        </form>
    @else
        <a href="{{ route('auth.google') }}" class="btn btn-primary">Link Google Account</a>
    @endif
    <br><br>
    @if($user->github_id)
        <form method="POST" action="{{ route('profile.unlinkProvider', 'github') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Unlink GitHub Account</button>
        </form>
    @else
        <a href="{{ route('auth.github') }}" class="btn btn-primary">Link GitHub Account</a>
    @endif

    <h2>Reset Password</h2>
    <form method="POST" action="{{ route('profile.sendResetCode') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Send Reset Code</button>
    </form>
    <form method="POST" action="{{ route('profile.verifyResetCode') }}">
        @csrf
        <div class="mb-3">
            <label for="reset_code" class="form-label">Reset Code</label>
            <input type="text" class="form-control" id="reset_code" name="reset_code">
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
@endsection