<?php
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find a teacher with a subject value
$teacher = \App\Models\Teacher::where('subject', '!=', null)->first();

if (!$teacher) {
    echo "No teacher with subject found.\n";
    exit;
}

echo "Teacher: " . $teacher->first_name . " " . $teacher->last_name . "\n";
echo "Subject value: " . $teacher->subject . "\n";
echo "Subject ID value: " . ($teacher->subject_id ?? 'NULL') . "\n\n";

// Get subject information
$subjectInfo = null;
if ($teacher->subject_id && $teacher->subject) {
    $subjectModel = \App\Models\Subject::find($teacher->subject_id);
    $subjectInfo = [
        'id' => $teacher->subject_id,
        'name' => $subjectModel ? $subjectModel->name : 'Unknown'
    ];
    echo "Using subject_id relation\n";
} else if ($teacher->subject) {
    // Check if subject is a numeric ID
    if (is_numeric($teacher->subject)) {
        // Try to find the subject by ID
        $subjectModel = \App\Models\Subject::find($teacher->subject);
        if ($subjectModel) {
            $subjectInfo = [
                'id' => $subjectModel->id,
                'name' => $subjectModel->name
            ];
            echo "Found subject by numeric ID\n";
        } else {
            // Fallback to just using the subject value
            $subjectInfo = $teacher->subject;
            echo "No subject found for ID: " . $teacher->subject . "\n";
        }
    } else {
        // If it's not numeric, use as is
        $subjectInfo = $teacher->subject;
        echo "Using subject as string value\n";
    }
}

echo "Subject info: " . json_encode($subjectInfo, JSON_PRETTY_PRINT) . "\n";

// List all subjects for reference
echo "\nAll subjects in database:\n";
$subjects = \App\Models\Subject::all();
foreach ($subjects as $subject) {
    echo "ID: {$subject->id}, Name: {$subject->name}\n";
} 