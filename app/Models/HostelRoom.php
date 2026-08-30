<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'hostel_id',
        'room_type_id',
        'room_number',
        'beds',
        'description',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'beds' => 'integer',
        'status' => 'boolean',
    ];
    
    /**
     * Get the school that owns the room.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the hostel that owns the room.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
    
    /**
     * Get the room type of this room.
     */
    public function roomType()
    {
        return $this->belongsTo(HostelRoomType::class, 'room_type_id');
    }
}
