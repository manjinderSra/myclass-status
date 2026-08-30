<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'route_detail_id',
        'vehicle_id',
        'driver_id',
        'status'
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
     * Get the school that this assignment belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the route that this assignment belongs to.
     */
    public function route()
    {
        return $this->belongsTo(RouteDetail::class, 'route_detail_id');
    }

    /**
     * Get the vehicle assigned to this route.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver assigned to this route.
     */
    public function driver()
    {
        return $this->belongsTo(VehicleDriver::class, 'driver_id');
    }
} 