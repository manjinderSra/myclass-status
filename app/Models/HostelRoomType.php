<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRoomType extends Model
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
        'description',
        'price',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'status' => 'boolean',
    ];
    
    /**
     * Get the school that owns the room type.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the rooms that are of this type.
     */
    public function rooms()
    {
        return $this->hasMany(HostelRoom::class, 'room_type_id');
    }
} 