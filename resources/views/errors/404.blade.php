@extends('errors.frame')

@section('code', '404')
@section('message', 'Page not found')
@section('icon', '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>')

@section('hint', "The page you're looking for doesn't exist — it may have moved, or the event you wanted has ended.")

@section('actions')
    <a href="{{ url('/') }}" class="btn-primary">Back to home</a>
    <button type="button" onclick="history.back()" class="btn-secondary">Go back</button>
@endsection
