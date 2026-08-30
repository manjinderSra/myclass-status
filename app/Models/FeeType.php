<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unique_id',
        'school_id',
        'fee_group_id',
        'name',
        'fees_code',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the fee group that this fee type belongs to.
     */
    // public function feeGroup()
    // {
    //     return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    // }

    /**
     * Get the school that this fee type belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class, 'fee_group_id', 'id');
    }
} 