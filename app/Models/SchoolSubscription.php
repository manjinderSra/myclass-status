<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'plan_id',
        'start_date',
        'end_date',
        'status', // active, expired, cancelled
        'price_paid',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the school that owns the subscription
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the plan associated with this subscription
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
} 