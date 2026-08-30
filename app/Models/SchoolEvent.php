<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'program_id',
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'organizer',
        'image_path',
        'status',
        'is_featured'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school that owns the event.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the program that the event belongs to.
     */
    public function program()
    {
        return $this->belongsTo(SchoolProgram::class, 'program_id');
    }

    /**
     * Get the full URL for the event image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope a query to only include ongoing events.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope a query to only include completed events.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
            ->orWhere(function($query) {
                $query->where('event_date', '<', now()->toDateString())
                    ->where('status', '!=', 'cancelled');
            })
            ->orderBy('event_date', 'desc');
    }

    /**
     * Scope a query to only include featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
