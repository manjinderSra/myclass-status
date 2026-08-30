@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                General Settings / <span class="text-l text-gray-500">Terms and Conditions</span>
            </h1>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-bs-toggle="modal" data-bs-target="#editTermsModal">
                <i class="ri-edit-box-line align-middle me-1"></i> Update Terms
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-green-700">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-red-700">&times;</span>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $termsCondition->title ?? 'School Terms and Conditions' }}
                </h2>
                
                @if($termsCondition)
                    <div class="text-sm text-gray-500">
                        Version: {{ $termsCondition->version }} | Last Updated: {{ $termsCondition->updated_at->format('M d, Y') }}
                    </div>
                @endif
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                @if($termsCondition)
                    <div class="prose prose-blue max-w-none terms-content">
                        {!! $termsCondition->content !!}
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    No terms and conditions have been added yet. Please use the "Update Terms" button to add your school's terms and conditions.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div id="editTermsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" aria-labelledby="editTermsModalLabel" aria-hidden="true">
    <div class="bg-white rounded-lg max-w-4xl w-full p-6 relative max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-semibold" id="editTermsModalLabel">Edit Terms and Conditions</h5>
            <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('school.termsCondition.update') }}" method="POST" id="termsForm">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="title" name="title" value="{{ $termsCondition->title ?? 'School Terms and Conditions' }}" required>
            </div>
            
            <div class="mb-4">
                <label for="version" class="block text-sm font-medium text-gray-700 mb-1">Version</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="version" name="version" value="{{ $termsCondition->version ?? '1.0' }}" required>
                <p class="mt-1 text-sm text-gray-500">Example: 1.0, 1.1, 2.0, etc.</p>
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea class="w-full" id="content" name="content">{{ $termsCondition->content ?? '' }}</textarea>
                <div class="mt-1 text-sm text-gray-500">Use the formatting tools to style your terms and conditions.</div>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- File Upload Modal -->
<div id="uploadTermsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" aria-labelledby="uploadTermsModalLabel" aria-hidden="true">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-semibold" id="uploadTermsModalLabel">Upload Terms and Conditions</h5>
            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeUploadModal()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="uploadTermsForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="upload_title" name="title" required>
            </div>
            
            <div class="mb-4">
                <label for="version" class="block text-sm font-medium text-gray-700 mb-1">Version</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="upload_version" name="version" value="1.0" required>
            </div>
            
            <div class="mb-4">
                <label for="terms_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File (TXT, PDF, DOC, DOCX)</label>
                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="terms_file" name="terms_file" accept=".txt,.pdf,.doc,.docx" required>
                <p class="mt-1 text-sm text-gray-500">Maximum file size: 5MB</p>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500" onclick="closeUploadModal()">Cancel</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="processUpload()">Upload</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/j2m26a2zqx49l6cq9odk8l2qie4yefbz35ld1i8uk8jz0dub/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
        
        // Handle form submission to ensure TinyMCE content is included
        document.getElementById('termsForm').addEventListener('submit', function(e) {
            // Get the content from TinyMCE
            const content = tinymce.get('content').getContent();
            if (!content) {
                e.preventDefault();
                alert('Please enter terms and conditions content');
                return false;
            }
        });
        
        // Initialize modal functionality
        const editTermsBtn = document.querySelector('[data-bs-target="#editTermsModal"]');
        const closeModalBtns = document.querySelectorAll('[data-bs-dismiss="modal"]');
        const editTermsModal = document.getElementById('editTermsModal');
        const uploadTermsModal = document.getElementById('uploadTermsModal');
        
        // Add Upload button next to Edit button
      
      
        
        if (editTermsBtn && editTermsModal) {
            editTermsBtn.addEventListener('click', function() {
                editTermsModal.classList.remove('hidden');
            });
        }
        
        if (closeModalBtns.length > 0) {
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = this.closest('.fixed');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === editTermsModal) {
                editTermsModal.classList.add('hidden');
            }
            if (event.target === uploadTermsModal) {
                uploadTermsModal.classList.add('hidden');
            }
        });
    });
    
    function closeUploadModal() {
        document.getElementById('uploadTermsModal').classList.add('hidden');
    }
    
    function processUpload() {
        const fileInput = document.getElementById('terms_file');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Please select a file to upload');
            return;
        }
        
        const title = document.getElementById('upload_title').value;
        const version = document.getElementById('upload_version').value;
        
        if (!title || !version) {
            alert('Please fill in all required fields');
            return;
        }
        
        const formData = new FormData();
        formData.append('title', title);
        formData.append('version', version);
        formData.append('terms_file', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        // Show loading indicator
        const uploadBtn = document.querySelector('#uploadTermsForm button[type="button"]:last-child');
        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = 'Processing...';
        uploadBtn.disabled = true;
        
        fetch('{{ route("school.termsCondition.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Terms and conditions uploaded successfully');
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during upload. Please try again.');
            uploadBtn.innerHTML = originalText;
            uploadBtn.disabled = false;
        });
    }
</script>

<style>
    .terms-content {
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }
    .terms-content h1, 
    .terms-content h2, 
    .terms-content h3, 
    .terms-content h4, 
    .terms-content h5, 
    .terms-content h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .terms-content h1 { font-size: 1.8rem; }
    .terms-content h2 { font-size: 1.5rem; }
    .terms-content h3 { font-size: 1.3rem; }
    .terms-content p {
        margin-bottom: 1rem;
    }
    .terms-content ul, 
    .terms-content ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .terms-content ul li, 
    .terms-content ol li {
        margin-bottom: 0.5rem;
    }
    .terms-content table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 1rem;
    }
    .terms-content table td, 
    .terms-content table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .terms-content table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .terms-content table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #f8f9fa;
    }
</style> 