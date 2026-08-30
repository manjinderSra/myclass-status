
@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex" >
    @include('client.schoolPanel.layout.sidebar')

    <!-- Main Content -->
     <div class="flex-1 min-h-screen overflow-y-auto px-6 py-24 bg-gray-50 relative" x-data="{ showPasswordModal: false, showDeactivateModal: false, showDeleteModal: false }">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">General Settings / <span class="text-l text-gray-500">Account Settings</span></h1>

        <div class="space-y-6">
            <!-- Password Section -->
            <div class="p-6 bg-white rounded-lg shadow-md transition-all duration-500">
                <h2 class="text-xl font-semibold text-gray-800">Password</h2>
                <p class="mt-2 text-gray-600">Set a unique password to protect your account.</p>
                <button @click="showPasswordModal = true" class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Change Password
                </button>
            </div>

            <!-- Deactivate Section -->
            <div class="p-6 bg-white rounded-lg shadow-md transition-all duration-500">
                <h2 class="text-xl font-semibold text-gray-800">Deactivate Account</h2>
                <p class="mt-2 text-gray-600">Temporarily deactivate your account. You can reactivate it by logging in again.</p>
                <button @click="showDeactivateModal = true" class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Deactivate Account
                </button>
            </div>

            <!-- Delete Section -->
            <div class="p-6 bg-white rounded-lg shadow-md transition-all duration-500">
                <h2 class="text-xl font-semibold text-gray-800">Delete Account</h2>
                <p class="mt-2 text-gray-600">Permanently delete your account. This cannot be undone.</p>
                <button @click="showDeleteModal = true" class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Delete Account
                </button>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div x-show="showPasswordModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-transition>
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>
                <form>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="button" @click="showPasswordModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Deactivate Modal -->
        <div x-show="showDeactivateModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-transition>
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-lg font-semibold text-red-800">Are you sure?</h2>
                <p class="mt-2 text-sm text-gray-600">Do you really want to deactivate your account?</p>
                <div class="flex justify-end mt-6 space-x-4">
                    <button @click="showDeactivateModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-transition>
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-lg font-semibold text-red-800">Delete Account?</h2>
                <p class="mt-2 text-sm text-gray-600">This action cannot be undone. Are you sure you want to proceed?</p>
                <div class="flex justify-end mt-6 space-x-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')
