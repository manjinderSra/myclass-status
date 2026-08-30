<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Teacher Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.12.0/dist/cdn.min.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Add any custom CSS here */
        .main-content {
            margin-left: 16rem; /* Sidebar width */
            min-height: calc(100vh - 5rem); /* Full height minus topbar */
            padding-top: 5rem; /* Height of topbar */
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @yield('styles')
</head>

<body class="bg-gray-100">
    <!-- Topbar -->
    @include('client.teacher.layout.topbar')

    <!-- Sidebar -->
    @include('client.teacher.layout.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    @yield('scripts')
</body>

</html> 