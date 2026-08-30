<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'description',
    ];

    /**
     * Get the school that owns the category
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the rules for the category
     */
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }
} 