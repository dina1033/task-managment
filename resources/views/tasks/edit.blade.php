@extends('layouts.app')
@section('content')
    <h1 class="text-2xl mb-4 font-bold">Edit Task</h1>
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @method('PUT')
        @include('tasks._form', ['buttonText' => 'Update'])
    </form>
@endsection
