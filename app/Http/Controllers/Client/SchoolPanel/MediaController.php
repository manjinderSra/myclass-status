<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\School;
use Intervention\Image\Laravel\Facades\Image;

class MediaController extends Controller
{
    /**
     * Display a listing of the school media.
     *
     * @return \Illuminate\Http\Response
     */
     
                    private function getSchoolId()
    {
        $user = Auth::user();
        $schoolId = null;
        
        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
        }
        
        return $schoolId;
    }
     
     
     
    public function index(Request $request)
    {
        $schoolId = $this->getSchoolId();
        $type = $request->input('type', 'all');
        $category = $request->input('category');
        
        $query = SchoolMedia::where('school_id', $schoolId);
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $media = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = SchoolMedia::where('school_id', $schoolId)
            ->select('category')
            ->distinct()
            ->pluck('category');
            
        return view('client.schoolPanel.media.index', compact('media', 'categories', 'type', 'category'));
    }

    /**
     * Show the form for creating a new media.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.schoolPanel.media.create');
    }

    /**
     * Store a newly created media in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:photo,video',
            'file' => 'required|file|max:50000', // 50MB max
            'category' => 'required|string|max:50',
            'is_featured' => 'sometimes|boolean'
        ]);
        
        $schoolId = $this->getSchoolId();
        $type = $request->input('type');
        
       
            $file = $request->file('file');
            $filePath = null;
            $thumbnailPath = null;
            
            if ($type === 'photo') {
                // Validate photo
                $request->validate([
                    'file' => 'mimes:jpeg,png,jpg,gif|max:50000', // 50MB max for photos
                ]);
                
                // Store the original photo
                $filePath = $file->store('school-media/photos/' . $schoolId, 'public');
                
                // Create thumbnail
                $thumbnail = Image::read($file);
                $thumbnail = $thumbnail->cover(300, 300);
                
                $thumbnailPath = 'school-media/photos/' . $schoolId . '/thumbnails/' . basename($filePath);
                Storage::disk('public')->put($thumbnailPath, $thumbnail->toJpeg());
                
            } elseif ($type === 'video') {
                // Validate video
                $request->validate([
                    'file' => 'mimes:mp4,mov,avi,wmv|max:50000', // 50MB max for videos
                    'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg|max:2000', // Optional thumbnail
                ]);
                
                // Store the video
                $filePath = $file->store('school-media/videos/' . $schoolId, 'public');
                
                // Handle thumbnail if provided
                if ($request->hasFile('thumbnail')) {
                    $thumbnail = $request->file('thumbnail');
                    $thumbnailPath = $thumbnail->store('school-media/videos/' . $schoolId . '/thumbnails', 'public');
                }
            }
            
            // Create media record
            SchoolMedia::create([
                'school_id' => $schoolId,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $type,
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'category' => $request->category,
                'is_featured' => $request->boolean('is_featured', false),
                'status' => 'active'
            ]);
            
            return redirect()->route('school.media.index')
                ->with('success', ucfirst($type) . ' uploaded successfully!');
                
     
    }

    /**
     * Display the specified media.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schoolId = $this->getSchoolId();
        $media = SchoolMedia::where('school_id', $schoolId)
            ->findOrFail($id);
            
        return view('client.schoolPanel.media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified media.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schoolId = $this->getSchoolId();
        $media = SchoolMedia::where('school_id', $schoolId)
            ->findOrFail($id);
            
        return view('client.schoolPanel.media.edit', compact('media'));
    }

    /**
     * Update the specified media in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:50',
            'is_featured' => 'sometimes|boolean',
            'status' => 'sometimes|in:active,inactive'
        ]);
        
        $schoolId = $this->getSchoolId();
        $media = SchoolMedia::where('school_id', $schoolId)
            ->findOrFail($id);
            
        try {
            // Update thumbnail if provided
            if ($request->hasFile('thumbnail')) {
                $request->validate([
                    'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2000',
                ]);
                
                // Delete old thumbnail if exists
                if ($media->thumbnail_path && Storage::disk('public')->exists($media->thumbnail_path)) {
                    Storage::disk('public')->delete($media->thumbnail_path);
                }
                
                // Store new thumbnail
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('school-media/' . ($media->type === 'photo' ? 'photos' : 'videos') . '/' . $schoolId . '/thumbnails', 'public');
                $media->thumbnail_path = $thumbnailPath;
            }
            
            // Update media record
            $media->title = $request->title;
            $media->description = $request->description;
            $media->category = $request->category;
            $media->is_featured = $request->boolean('is_featured', false);
            
            if ($request->has('status')) {
                $media->status = $request->status;
            }
            
            $media->save();
            
            return redirect()->route('school.media.index')
                ->with('success', ucfirst($media->type) . ' updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error updating media: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified media from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();
        $media = SchoolMedia::where('school_id', $schoolId)
            ->findOrFail($id);
            
        try {
            // Delete files from storage
            if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
            
            if ($media->thumbnail_path && Storage::disk('public')->exists($media->thumbnail_path)) {
                Storage::disk('public')->delete($media->thumbnail_path);
            }
            
            // Delete record
            $media->delete();
            
            return redirect()->route('school.media.index')
                ->with('success', ucfirst($media->type) . ' deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting media: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while deleting. Please try again.');
        }
    }
    
    /**
     * Toggle the featured status of the specified media.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleFeatured($id)
    {
        $schoolId = $this->getSchoolId();
        $media = SchoolMedia::where('school_id', $schoolId)
            ->findOrFail($id);
            
        try {
            $media->is_featured = !$media->is_featured;
            $media->save();
            
            return redirect()->back()
                ->with('success', ucfirst($media->type) . ' ' . ($media->is_featured ? 'featured' : 'unfeatured') . ' successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error toggling featured status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred. Please try again.');
        }
    }
} 