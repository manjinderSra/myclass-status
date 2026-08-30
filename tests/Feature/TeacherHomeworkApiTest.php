<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Homework;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

class TeacherHomeworkApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $school;
    protected $teacher;
    protected $user;
    protected $subject;
    protected $class;
    protected $section;
    protected $timetable;
    protected $timetablePeriod;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test school
        $this->school = School::factory()->create();

        // Create test user for teacher
        $this->user = User::factory()->create([
            'school_id' => $this->school->id,
            'role' => 'teacher'
        ]);

        // Create test subject
        $this->subject = Subject::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Mathematics',
        ]);

        // Create test teacher
        $this->teacher = Teacher::factory()->create([
            'school_id' => $this->school->id,
            'user_id' => $this->user->id,
            'subject_id' => $this->subject->id,
        ]);

        // Create test class
        $this->class = SchoolClass::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Class 10',
        ]);

        // Create test section
        $this->section = Section::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'name' => 'A',
        ]);

        // Create test timetable
        $this->timetable = TimeTable::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'section_id' => $this->section->id,
        ]);

        // Create test timetable period with teacher
        $this->timetablePeriod = TimeTablePeriod::factory()->create([
            'timetable_id' => $this->timetable->id,
            'teacher' => $this->teacher->id,
            'subject' => $this->subject->id,
        ]);

        // Setup storage for file uploads
        Storage::fake('public');
    }

    /**
     * Test getting all homework for a teacher.
     */
    public function test_get_all_homework_for_teacher()
    {
        // Create test homework for the teacher
        $homework = Homework::factory()->create([
            'school_id' => $this->school->id,
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'created_by' => $this->user->id,
        ]);

        // Authenticate as teacher
        Sanctum::actingAs($this->user);

        // Make request to get all homework
        $response = $this->getJson('/api/teacher/homework');

        // Assert response is successful and contains the homework
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'homework',
                    'teaching_assignments',
                    'subject',
                ]
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test creating a new homework assignment.
     */
    public function test_create_homework()
    {
        // Authenticate as teacher
        Sanctum::actingAs($this->user);

        // Create test image
        $file = UploadedFile::fake()->image('homework.jpg');

        // Make request to create homework
        $response = $this->postJson('/api/teacher/homework', [
            'class' => $this->class->name,
            'section' => $this->section->id,
            'homework_date' => now()->format('Y-m-d'),
            'submission_date' => now()->addDays(5)->format('Y-m-d'),
            'description' => 'Test homework description',
            'image' => $file,
        ]);

        // Assert response is successful and homework was created
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'homework' => [
                    'id',
                    'class_name',
                    'section_id',
                    'subject_id',
                    'homework_date',
                    'submission_date',
                    'description',
                    'image_url',
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Homework added successfully',
            ]);

        // Assert homework exists in database
        $this->assertDatabaseHas('homework', [
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'description' => 'Test homework description',
            'created_by' => $this->user->id,
        ]);

        // Assert image was stored
        $homework = Homework::latest()->first();
        $this->assertNotNull($homework->image_path);
        Storage::disk('public')->assertExists($homework->image_path);
    }

    /**
     * Test getting details of a specific homework.
     */
    public function test_get_homework_details()
    {
        // Create test homework
        $homework = Homework::factory()->create([
            'school_id' => $this->school->id,
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'created_by' => $this->user->id,
        ]);

        // Authenticate as teacher
        Sanctum::actingAs($this->user);

        // Make request to get homework details
        $response = $this->getJson("/api/teacher/homework/{$homework->id}");

        // Assert response is successful and contains homework details
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'class_name',
                    'section',
                    'subject',
                    'homework_date',
                    'submission_date',
                    'description',
                    'image_url',
                    'created_by',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $homework->id,
                    'class_name' => $this->class->name,
                ]
            ]);
    }

    /**
     * Test updating a homework assignment.
     */
    public function test_update_homework()
    {
        // Create test homework
        $homework = Homework::factory()->create([
            'school_id' => $this->school->id,
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'created_by' => $this->user->id,
            'description' => 'Original description',
        ]);

        // Authenticate as teacher
        Sanctum::actingAs($this->user);

        // Create updated image
        $file = UploadedFile::fake()->image('updated_homework.jpg');

        // Make request to update homework
        $response = $this->putJson("/api/teacher/homework/{$homework->id}", [
            'class' => $this->class->name,
            'section' => $this->section->id,
            'homework_date' => now()->format('Y-m-d'),
            'submission_date' => now()->addDays(7)->format('Y-m-d'),
            'description' => 'Updated description',
            'image' => $file,
        ]);

        // Assert response is successful
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'homework',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Homework updated successfully',
                'homework' => [
                    'id' => $homework->id,
                    'description' => 'Updated description',
                ]
            ]);

        // Assert homework was updated in database
        $this->assertDatabaseHas('homework', [
            'id' => $homework->id,
            'description' => 'Updated description',
        ]);
    }

    /**
     * Test deleting a homework assignment.
     */
    public function test_delete_homework()
    {
        // Create test homework with image
        $file = UploadedFile::fake()->image('homework.jpg');
        $imagePath = $file->store('homework_images', 'public');

        $homework = Homework::factory()->create([
            'school_id' => $this->school->id,
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'created_by' => $this->user->id,
            'image_path' => $imagePath,
        ]);

        // Authenticate as teacher
        Sanctum::actingAs($this->user);

        // Make request to delete homework
        $response = $this->deleteJson("/api/teacher/homework/{$homework->id}");

        // Assert response is successful
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Homework deleted successfully',
            ]);

        // Assert homework was deleted from database
        $this->assertDatabaseMissing('homework', [
            'id' => $homework->id,
        ]);

        // Assert image was deleted from storage
        Storage::disk('public')->assertMissing($imagePath);
    }

    /**
     * Test that a teacher cannot update another teacher's homework.
     */
    public function test_cannot_update_other_teachers_homework()
    {
        // Create another teacher
        $otherUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role' => 'teacher'
        ]);

        $otherTeacher = Teacher::factory()->create([
            'school_id' => $this->school->id,
            'user_id' => $otherUser->id,
        ]);

        // Create homework by the other teacher
        $homework = Homework::factory()->create([
            'school_id' => $this->school->id,
            'class_name' => $this->class->name,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'created_by' => $otherUser->id,
        ]);

        // Authenticate as our test teacher
        Sanctum::actingAs($this->user);

        // Try to update the homework
        $response = $this->putJson("/api/teacher/homework/{$homework->id}", [
            'class' => $this->class->name,
            'section' => $this->section->id,
            'homework_date' => now()->format('Y-m-d'),
            'submission_date' => now()->addDays(5)->format('Y-m-d'),
            'description' => 'Trying to update other teacher\'s homework',
        ]);

        // Assert response is forbidden
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You are not authorized to update this homework',
            ]);

        // Assert homework was not updated
        $this->assertDatabaseMissing('homework', [
            'id' => $homework->id,
            'description' => 'Trying to update other teacher\'s homework',
        ]);
    }
} 