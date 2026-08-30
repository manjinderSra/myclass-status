<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'primary_contact',
        // 'subject',
        'subject_id',
        'date_of_birth',
        'date_of_joining',
        'blood_group',
        'father_name',
        'mother_name',
        'marital_status',
        'languages_known',
        'qualification',
        'work_experience',
        'previous_school',
        'previous_school_address',
        'previous_school_phone',
        'pan_number',
        'status',
        'notes',
        'current_address',
        'permanent_address',
        'epf_no',
        'basic_salary',
        'contract_type',
        'work_shift',
        'work_location',
        'date_of_leaving',
        'medical_leaves',
        'casual_leaves',
        'maternity_leaves',
        'sick_leaves',
        'bank_name',
        'branch',
        'ifsc_number',
        'other_information',
        'transport_enabled',
        'pickup_point_id',
        'hostel_enabled',
        'hostel_id',
        'room_id',
        'profile_image',
        'medical_condition_document',
        'transfer_certificate_document',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'date_of_leaving' => 'date',
        'transport_enabled' => 'boolean',
        'hostel_enabled' => 'boolean',
        'basic_salary' => 'decimal:2',
    ];

    /**
     * Get the school that the teacher belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the pickup point associated with the teacher.
     */
    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    /**
     * Get the hostel associated with the teacher.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the room associated with the teacher.
     */
    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }

    /**
     * Get the subject associated with the teacher.
     */

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    

    /**
     * Get the classes associated with the teacher.
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_class', 'teacher_id', 'class_id');
    }

    /**
     * Get the full name of the teacher.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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
}
