<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'vehicle_no',
        'vehicle_model',
        'made_year',
        'registration_no',
        'chassis_no',
        'seat_capacity',
        'gps_tracking_id',
        'driver_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'seat_capacity' => 'integer',
    ];

    /**
     * Get the school that the vehicle belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the driver assigned to the vehicle.
     */
    public function driver()
    {
        return $this->belongsTo(VehicleDriver::class, 'driver_id');
    }
}
