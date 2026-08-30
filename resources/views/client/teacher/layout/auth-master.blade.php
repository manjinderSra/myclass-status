<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Portal')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery CDN (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-K+ctZQ+4gZh8sE2XvLXqFQbC+VUEvIO0+LKh6uHQE1k=" crossorigin="anonymous"></script>

    <!-- AlpineJS CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('styles')
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen">
        @yield('content')
    </div>

    @yield('scripts')
</body>

</html> 