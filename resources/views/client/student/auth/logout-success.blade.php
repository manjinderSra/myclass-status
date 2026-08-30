@include('client.student.layout.header')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
    <div class="mb-6 inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Successfully Logged Out</h2>
    <p class="text-gray-600 mb-6">You have been successfully logged out of your student account.</p>
    <a href="{{ route('student.login') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
      Sign In Again
    </a>
    <div class="mt-6">
      <a href="/" class="text-sm text-indigo-600 hover:text-indigo-500">Return to Home Page</a>
    </div>
  </div>
</div>

@include('client.student.layout.footer') 