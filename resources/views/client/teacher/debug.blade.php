<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Page</title>
</head>
<body>
    <h1>Debug Page</h1>
    <p>If you can see this page, basic rendering is working.</p>
    
    <h2>Session Data:</h2>
    <pre>
    @if(Session::has('teacher_id'))
        Teacher ID: {{ Session::get('teacher_id') }}
        Teacher Name: {{ Session::get('teacher_name') }}
        Logged in as: {{ Session::get('logged_in_as') }}
    @else
        No teacher session data found.
    @endif
    </pre>
    
    <h2>Links:</h2>
    <ul>
        <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('teacher.login') }}">Login</a></li>
    </ul>
</body>
</html> 