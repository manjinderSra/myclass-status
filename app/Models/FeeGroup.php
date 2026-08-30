<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the school that owns the fee group.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

        public function feeTypes()
    {
        return $this->hasMany(FeeType::class, 'fee_group_id', 'id');
    }
    public function feesGroup()
    {
        return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    }

    public function feesType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

} 