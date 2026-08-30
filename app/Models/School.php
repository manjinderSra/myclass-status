<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'admin_name',
        'email',
        'phone',
        'address',
        'logo',
        'status', // active, inactive, pending
        'registration_date',
        'admin_id',
        'tagline',
        'website',
        'about',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    /**
     * Get the subscriptions for the school
     */
    public function subscriptions()
    {
        return $this->hasMany(SchoolSubscription::class);
    }

    /**
     * Get the active subscription for the school
     */
    public function activeSubscription()
    {
        return $this->hasOne(SchoolSubscription::class)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->latest();
    }

    /**
     * Get the users associated with this school
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the admin user of the school
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the rule categories for the school
     */
    public function ruleCategories()
    {
        return $this->hasMany(RuleCategory::class);
    }

    /**
     * Get the rules for the school
     */
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * Get the hostels associated with this school
     */
    public function hostels()
    {
        return $this->hasMany(Hostel::class);
    }

    /**
     * Get the hostel room types associated with this school
     */
    public function hostelRoomTypes()
    {
        return $this->hasMany(HostelRoomType::class);
    }

    /**
     * Get the programs associated with this school
     */
    public function programs()
    {
        return $this->hasMany(SchoolProgram::class);
    }

    /**
     * Get the events associated with this school
     */
    public function events()
    {
        return $this->hasMany(SchoolEvent::class);
    }

    /**
     * Get the upcoming events for this school
     */
    public function upcomingEvents()
    {
        return $this->hasMany(SchoolEvent::class)
            ->where('status', 'upcoming')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }

    /**
     * Get the featured programs for this school
     */
    public function featuredPrograms()
    {
        return $this->hasMany(SchoolProgram::class)
            ->where('is_featured', true)
            ->where('status', 'active');
    }

    /**
     * Get the featured events for this school
     */
    public function featuredEvents()
    {
        return $this->hasMany(SchoolEvent::class)
            ->where('is_featured', true)
            ->where('status', '!=', 'cancelled')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }
    
    
    public function students()
{
    return $this->hasMany(Student::class);
}

public function teachers()
{
    return $this->hasMany(Teacher::class);
}
} 