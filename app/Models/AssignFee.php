<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignFee extends Model
{
    use HasFactory;

    protected $table = 'assign_fee';

    protected $fillable = [
        'fee_group_id',
        'fee_type_id',
        'fee_master_id',
        'student_id',
    ];


    // 🔗 Relationships

    // public function feeGroup()
    // {
    //     return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    // }

    // public function feeType()
    // {
    //     return $this->belongsTo(FeeType::class, 'fee_type_id');
    // }

    // public function feeMaster()
    // {
    //     return $this->belongsTo(FeeMaster::class, 'fee_master_id');
    // }

    // public function student()
    // {
    //     return $this->belongsTo(Student::class, 'student_id');
    // }
public function getBalanceAttribute()
{
    $total = $this->feeMaster->amount ?? 0;
    $paid = $this->collectFee->paid_amount ?? 0;
    return $total - $paid;
}

public function getPaidAmountAttribute()
{
    return $this->collectFee->paid_amount ?? 0;
}

    public function collectFee()
    {
        return $this->hasOne(\App\Models\CollectFee::class, 'assign_fee_id', 'id');
    }
    public function student()
{
    return $this->belongsTo(\App\Models\Student::class, 'student_id', 'id');
}

public function feeMaster()
{
    return $this->belongsTo(\App\Models\FeeMaster::class, 'fee_master_id', 'id');
}

public function feeGroup()
{
    return $this->belongsTo(\App\Models\FeeGroup::class, 'fee_group_id', 'id');
}

public function feeType()
{
    return $this->belongsTo(\App\Models\FeeType::class, 'fee_type_id', 'id');
}

}
