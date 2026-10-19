@extends('errors.frame')

@section('code', '500')
@section('message', 'Something went wrong')
@section('icon', '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>')

@section('hint', 'An unexpected error occurred on our end. Please try again in a moment.')

@section('actions')
    <a href="{{ url('/') }}" class="btn-primary">Back to home</a>
    <button type="button" onclick="location.reload()" class="btn-secondary">Try again</button>
@endsection
