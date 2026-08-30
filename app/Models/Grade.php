<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    
    
    
    protected $fillable = [
    'school_id', 'name', 'min_score', 'max_score', 'description', 'status'
];



public function school()
{
    return $this->belongsTo(School::class);
}

}
