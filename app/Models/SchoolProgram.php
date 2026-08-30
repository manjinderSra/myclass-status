<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'coordinator',
        'coordinator_contact',
        'image_path',
        'status',
        'start_date',
        'end_date',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school that owns the program.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the events for the program.
     */
    public function events()
    {
        return $this->hasMany(SchoolEvent::class, 'program_id');
    }

    /**
     * Get the full URL for the program image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    /**
     * Scope a query to only include active programs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured programs.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
