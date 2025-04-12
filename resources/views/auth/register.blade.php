@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-500">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring focus:ring-blue-200" required />
            </div>

            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring focus:ring-blue-200" required />
            </div>

            <div>
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring focus:ring-blue-200" required />
            </div>

            <div>
                <label class="block mb-1 font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring focus:ring-blue-200" required />
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700">
                Register
            </button>

            <div class="text-center text-sm mt-4">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login here</a>
            </div>
        </form>
    </div>
</div>
@endsection
