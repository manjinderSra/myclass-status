<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SubscriptionHelper;

class ExampleController extends Controller
{
    /**
     * Example of using the SubscriptionHelper in a controller method
     */
    public function createStudent(Request $request)
    {
        // Check if the school is within their student limit
        if (!SubscriptionHelper::isWithinUserLimit('students')) {
            return redirect()->back()->with('error', 'You have reached the maximum number of students allowed by your subscription plan. Please upgrade your plan to add more students.');
        }
        
        // Continue with student creation...
        
        // Example: count remaining slots
        $maxStudents = SubscriptionHelper::getMaxUserLimit('students');
        $currentStudents = SubscriptionHelper::getUserCount('students');
        
        // If maxStudents is 0, it means unlimited
        $remainingSlots = $maxStudents > 0 ? $maxStudents - $currentStudents : 'unlimited';
        
        return redirect()->back()->with('success', "Student created successfully. You have {$remainingSlots} student slots remaining.");
    }
    
    /**
     * Example of checking if a feature is available before showing a view
     */
    public function showExaminations()
    {
        // Check if the school has access to the examination_management feature
        if (!SubscriptionHelper::hasFeature('examination_management')) {
            return redirect()->route('school.dashboard')
                ->with('error', 'Your subscription plan does not include the Examination Management feature. Please upgrade your plan to access this feature.');
        }
        
        // Continue with showing examinations...
        return view('school.examinations.index');
    }
    
    /**
     * Example of checking a feature's allowed value
     */
    public function uploadFile(Request $request)
    {
        // Get the max file size allowed by the subscription
        $maxFileSizeMB = SubscriptionHelper::getFeatureValue('max_file_size') ?? 2; // Default to 2MB
        $maxFileSizeBytes = $maxFileSizeMB * 1024 * 1024; // Convert to bytes
        
        // Validate the file size
        $request->validate([
            'file' => "required|file|max:{$maxFileSizeBytes}"
        ]);
        
        // Continue with file upload...
        
        return redirect()->back()->with('success', 'File uploaded successfully.');
    }
} 