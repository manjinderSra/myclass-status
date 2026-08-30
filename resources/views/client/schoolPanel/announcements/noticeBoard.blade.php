@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')
<div x-data="{ showNoticeModal: {{ isset($editNotice) || $errors->any() ? 'true' : 'false' }} }" class="flex">
    @include('client.schoolPanel.layout.sidebar')
    
    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Announcements / <span class="text-lg text-gray-500">Notice Board</span></h1>
            <button @click="showNoticeModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Notice +
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Notice Board -->
        <div class="mt-8">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <ul class="space-y-4">
                    @if(isset($notices) && count($notices) > 0)
                        @foreach($notices as $notice)
                            <li class="border-l-4 border-blue-500 pl-4 relative group">
                                <h3 class="text-md font-semibold text-gray-800">{{ $notice->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $notice->message }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Posted on: {{ isset($notice->publish_date) ? $notice->publish_date->format('j M Y') : 'Not scheduled' }}</span>
                                    <div class="text-xs text-gray-400">
                                        To: {{ (isset($notice->recipients) && is_array($notice->recipients)) ? implode(', ', $notice->recipients) : 'All' }}
                                    </div>
                                </div>
                                
                                <!-- Action Buttons (visible on hover) -->
                                <div class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition flex space-x-2">
                                    <a href="{{ route('school.notices.edit', $notice->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('school.notices.destroy', $notice->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="text-center py-8">
                            <p class="text-gray-500">No notices found. Create a new notice to get started.</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Create/Edit Notice Modal -->
    <div x-show="showNoticeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" x-transition>
        <div class="bg-white p-6 rounded-lg w-full max-w-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                {{ isset($editNotice) ? 'Edit Notice' : 'Create Notice' }}
            </h2>
            <form action="{{ isset($editNotice) ? route('school.notices.update', $editNotice->id) : route('school.notices.store') }}" method="POST">
                @csrf
                @if(isset($editNotice))
                    @method('PUT')
                @endif
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input name="title" type="text" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:outline-none @error('title') border-red-500 @enderror" 
                            value="{{ old('title', isset($editNotice) ? $editNotice->title : '') }}" required>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" rows="3" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:outline-none @error('message') border-red-500 @enderror" required>{{ old('message', isset($editNotice) ? $editNotice->message : '') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Publish On</label>
                        <input name="publish_on" type="date" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:outline-none @error('publish_on') border-red-500 @enderror" 
                            value="{{ old('publish_on', isset($editNotice) && isset($editNotice->publish_date) ? $editNotice->publish_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                        @error('publish_on')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message To:</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['Student', 'Teacher', 'Admin', 'Library', 'Finance'] as $role)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="recipients[]" value="{{ $role }}" class="text-blue-600 focus:ring-blue-500 @error('recipients') border-red-500 @enderror"
                                        {{ (old('recipients') && in_array($role, old('recipients'))) || 
                                           (isset($editNotice) && isset($editNotice->recipients) && is_array($editNotice->recipients) && in_array($role, $editNotice->recipients)) ? 'checked' : '' }}>
                                    <span>{{ $role }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('recipients')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end mt-6 space-x-4">
                        <button type="button" @click="showNoticeModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ isset($editNotice) ? 'Update Notice' : 'Post Notice' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')
