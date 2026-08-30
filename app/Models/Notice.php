<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'school_id',
        'title',
        'message',
        'publish_date',
        'recipients',
        'created_by',
    ];
    
    protected $casts = [
        'publish_date' => 'date',
        'recipients' => 'array',
    ];
    
    /**
     * Get the school that owns the notice.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the user who created the notice.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
