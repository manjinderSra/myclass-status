<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable;

    protected $fillable = [
        'academic_year',
        'admission_number',
        'academic_number',
        'admission_date',
        'status',
        'first_name',
        'last_name',
        'class_id',
        'section_id',
        'school_id',
        'gender',
        'dob',
        'blood_group',
        'house',
        'religion',
        'category',
        'primary_contact',
        'email',
        'mother_tongue',
        'languages_known',
        'profile_image',
        'father_name',
        'father_email',
        'father_phone_number',
        'father_occupation',
        'father_profile_image',
        'mother_name',
        'mother_email',
        'mother_phone_number',
        'mother_occupation',
        'mother_profile_image',
        'guardian_type',
        'guardian_name',
        'guardian_relation',
        'guardian_email',
        'guardian_phone_number',
        'guardian_occupation',
        'guardian_address',
        'guardian_profile_image',
        'current_address',
        'permanent_address',
        'transport_enabled',
        'pickup_point_id',
        'hostel_enabled',
        'hostel_id',
        'room_id',
        'medical_condition_document',
        'transfer_certificate_document',
        'medical_condition_status',
        'allergies',
        'medications',
        'previous_school_name',
        'previous_school_address',
        'bank_name',
        'branch',
        'ifsc_number',
        'other_information',
        'student_id',
        'password',
        'siblings',
        'phone',
        'address',
        'date_of_birth',
        'is_active',
        'roll_number',
        'aadhaar_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'dob' => 'date',
        'languages_known' => 'array',
        'allergies' => 'array',
        'medications' => 'array',
        'transport_enabled' => 'boolean',
        'hostel_enabled' => 'boolean',
        'password' => 'hashed', // Automatically hash passwords
        'siblings' => 'array',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Generate a unique academic number
     * 
     * @param int $schoolId
     * @return string
     */
    public static function generateAcademicNumber($schoolId)
    {
        $year = date('Y');
        $month = date('m');
        $latestStudent = self::where('school_id', $schoolId)
            ->latest()
            ->first();
        
        $sequence = 1;
        if ($latestStudent) {
            // Extract sequence from existing academic number if possible
            $lastAcademicNumber = $latestStudent->academic_number;
            if (preg_match('/(\d+)$/', $lastAcademicNumber, $matches)) {
                $sequence = (int)$matches[1] + 1;
            }
        }
        
        return 'AC' . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique student ID
     * 
     * @return string
     */
    public static function generateStudentId()
    {
        $prefix = 'STU-';
        $randomString = strtoupper(Str::random(4)) . rand(1000, 9999);
        
        return $prefix . $randomString;
    }
public function hostel()
{
    return $this->belongsTo(\App\Models\Hostel::class, 'hostel_id');
}

public function room()
{
    return $this->belongsTo(\App\Models\HostelRoom::class, 'room_id');
}

    /**
     * Generate a random password
     * 
     * @param int $length
     * @return string
     */
    public static function generatePassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * Get the full name of the student
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Relationship with School
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // /**
    //  * Relationship with SchoolClass
    //  */
    // public function class()
    // {
    //     return $this->belongsTo(SchoolClass::class, 'class_id');
    // }

    // /**
    //  * Relationship with Section
    //  */
    // public function section()
    // {
    //     return $this->belongsTo(Section::class);
    // }

    public function class()
{
    return $this->belongsTo(SchoolClass::class, 'class_id');
}

public function section()
{
    return $this->belongsTo(Section::class, 'section_id');
}public function attendances()
{
    return $this->hasMany(StudentAttendance::class, 'student_id', 'id');
}


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // static::creating(function ($student) {
        //     // Get the last roll number for this class and section
        //     $lastRollNumber = static::where('class_id', $student->class_id)
        //         ->where('section_id', $student->section_id)
        //         ->whereNull('deleted_at')
        //         ->max('roll_number');

        //     // Assign next roll number
        //     $student->roll_number = $lastRollNumber ? $lastRollNumber + 1 : 1;
        // });
    }
} 