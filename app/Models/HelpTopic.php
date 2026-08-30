<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HelpTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'slug',
        'category',
        'description',
        'content',
        'icon',
        'status',
        'view_count',
        'created_by',
        'updated_by'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($topic) {
            // Generate a slug if not provided
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->title);
            }
            
            // Set the view count to 0 if not specified
            if (!isset($topic->view_count)) {
                $topic->view_count = 0;
            }
        });
    }

    /**
     * Get the school that owns this help topic.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who created this help topic.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this help topic.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Scope a query to only include published topics.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope a query to only include topics for a specific school.
     */
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
