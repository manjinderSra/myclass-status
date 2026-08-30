<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectFee extends Model
{
    use HasFactory;

    protected $table = 'collect_fees';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'assign_fee_id',
        'paid_amount',
        'balance',
        'collection_date',
        'payment_type',
        'payment_reference_no',
        'note',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'collection_date' => 'date',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * Relationship: A collected fee belongs to an assigned fee.
     */
    // public function assignFee()
    // {
    //     return $this->belongsTo(AssignFee::class, 'assign_fee_id');
    // }
    public function assignFee()
    {
        return $this->belongsTo(AssignFee::class, 'assign_fee_id', 'id');
    }
    /**
     * Accessor: Get formatted status.
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Helper: Check if fully paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Helper: Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Helper: Check if unpaid.
     */
    public function isUnpaid(): bool
    {
        return $this->status === 'unpaid';
    }
}
