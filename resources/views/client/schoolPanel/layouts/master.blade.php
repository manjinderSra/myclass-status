<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'School Dashboard')</title>

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
    @include('client.schoolPanel.layout.topbar')

    <!-- Sidebar -->
    @include('client.schoolPanel.layout.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    @include('client.schoolPanel.layout.footer')

    <!-- Scripts -->
    @yield('scripts')
</body>

</html> 