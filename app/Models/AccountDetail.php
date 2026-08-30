<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = [
        'school_id',
        'account_number',
        'ifsc',
        'name',
        'upi_id',
        'note',
    ];

    /**
     * Relation: Account belongs to School
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
