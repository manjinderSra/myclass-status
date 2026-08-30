<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'type',        // Boys, Girls, Co-ed
        'address',
        'intake',      // Total capacity
        'description',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'intake' => 'integer',
        'status' => 'boolean',
    ];
    
    /**
     * Get the school that owns the hostel.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the rooms for this hostel.
     */
    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }
} 