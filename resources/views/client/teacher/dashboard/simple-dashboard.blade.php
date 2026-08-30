<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Welcome, {{ Session::get('teacher_name', 'Teacher') }}!</h2>
            <p class="text-gray-600">This is a simplified dashboard for testing.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a></li>
                    <li>
                        <form method="POST" action="{{ route('teacher.logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Session Data</h3>
                <pre class="text-sm">
Teacher ID: {{ Session::get('teacher_id') }}
Teacher Name: {{ Session::get('teacher_name') }}
Logged in as: {{ Session::get('logged_in_as') }}
                </pre>
            </div>
        </div>
    </div>
</body>
</html> 