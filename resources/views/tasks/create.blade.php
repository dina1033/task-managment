@extends('layouts.app')
@section('content')
    <h1 class="text-2xl mb-4 font-bold">Create Task</h1>
    <form action="{{ route('tasks.store') }}" method="POST">
        @include('tasks._form', ['buttonText' => 'Create'])
    </form>
@endsection
