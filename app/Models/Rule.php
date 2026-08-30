<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'rule_category_id',
        'title',
        'description',
        'is_active',
    ];

    /**
     * Get the school that owns the rule
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the category that the rule belongs to
     */
    public function category()
    {
        return $this->belongsTo(RuleCategory::class, 'rule_category_id');
    }
} 