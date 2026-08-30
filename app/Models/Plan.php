<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'billing_cycle', // monthly, yearly
        'max_students',
        'max_teachers',
        'max_staff',
        'is_popular',
        'is_active',
    ];

    /**
     * Get the features associated with this plan
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features')
            ->withPivot('allowed_value')
            ->withTimestamps();
    }

    /**
     * Get the schools subscribed to this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(SchoolSubscription::class);
    }
} 