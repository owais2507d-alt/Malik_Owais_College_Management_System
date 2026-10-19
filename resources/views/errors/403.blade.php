@extends('errors.frame')

@section('code', '403')
@section('message', 'Access denied')
@section('icon', '<path d="M12 15v2M12 8v.01"/><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 14.14 14.14"/>')

@section('hint', 'You need to sign in with an account that has permission to view this area.')

@section('actions')
    <a href="{{ url('/') }}" class="btn-primary">Back to home</a>
    @guest
        <a href="{{ route('login') }}" class="btn-secondary">Sign in</a>
    @else
        <button type="button" onclick="history.back()" class="btn-secondary">Go back</button>
    @endguest
@endsection
