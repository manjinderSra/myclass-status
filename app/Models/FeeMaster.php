<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeMaster extends Model
{
    use HasFactory;

    protected $table = 'fee_masters'; 

    protected $fillable = [
        'school_id',
        'fee_group_id',
        'fee_type_id',
        'due_date',
        'amount',
        'fine_type',
        'fine_amount',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'status' => 'boolean',
    ];


    // Each FeeMaster belongs to one FeeGroup
    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class, 'fee_group_id');
        // return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    }

    // Each FeeMaster belongs to one FeeType
    public function feeType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    // Each FeeMaster belongs to a School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
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
