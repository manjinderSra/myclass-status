@extends('client.teacher.layout.auth-master')

@section('title', 'Logout Successful')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
        <div class="mb-4">
            <svg class="h-16 w-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Logout Successful</h2>
        <p class="text-gray-600 mb-8">You have been successfully logged out of your teacher account.</p>
        <a href="{{ route('teacher.login') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors">
            Sign In Again
        </a>
    </div>
</div>
@endsection 