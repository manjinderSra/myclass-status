<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'route_detail_id',
        'name',
        'latitude',
        'longitude',
        'sequence'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sequence' => 'integer',
    ];

    /**
     * Get the route that owns the pickup point.
     */
    public function routeDetail()
    {
        return $this->belongsTo(RouteDetail::class);
    }
}
