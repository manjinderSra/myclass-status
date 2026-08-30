<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modal Test Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Modal Test Page</h1>
        
        <div class="flex space-x-4 mb-8">
            <button onclick="openTestModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Open Test Modal
            </button>
        </div>
        
        <div class="mt-8">
            <p>This is a simple test page to check if modals work properly.</p>
            <p class="mt-4">If the modal opens when you click the button, then the issue is elsewhere in your main page.</p>
        </div>
    </div>
    
    <!-- Test Modal -->
    <div id="testModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Test Modal</h2>
            <p class="mb-6">If you can see this, the modal is working properly.</p>
            <div class="flex justify-end">
                <button onclick="closeTestModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    Close Modal
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function openTestModal() {
            console.log('Opening test modal');
            document.getElementById('testModal').classList.remove('hidden');
        }
        
        function closeTestModal() {
            console.log('Closing test modal');
            document.getElementById('testModal').classList.add('hidden');
        }
    </script>
</body>
</html> 