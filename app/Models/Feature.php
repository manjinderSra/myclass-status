<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'feature_group', // academics, finance, hrm, etc.
        'value_type', // boolean, number, text
        'is_active',
    ];

    /**
     * Get the plans that include this feature
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_features')
            ->withPivot('allowed_value')
            ->withTimestamps();
    }
} 