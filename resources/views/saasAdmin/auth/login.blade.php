@include('saasAdmin.layout.header')
<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Super Admin Sign In</h2>
    
    @if($errors->any())
    <div class="mb-4 p-3 text-sm rounded bg-red-100 text-red-800">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
    @endif
    
    @if(session('redirect_suggestion'))
    <div class="mb-4 p-3 text-sm rounded bg-blue-100 text-blue-800 flex items-center justify-between">
        <span>Looking for the School Panel?</span>
        <a href="{{ session('redirect_suggestion') }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">Go to School Panel</a>
    </div>
    @endif
    
    <form method="POST" action="{{ route('saasAdmin.login') }}" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input 
          type="email" 
          id="email"
          name="email"
          value="{{ old('email') }}"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
          placeholder="your@email.com"
          required
        />
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          type="password" 
          id="password"
          name="password"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
          placeholder="••••••••"
          required
        />
      </div>

  

      <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
        Sign In
      </button>
    </form>

  
  </div>
</div>
@include('saasAdmin.layout.footer')