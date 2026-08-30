<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'feature_id',
        'action'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($permission) {
            if (empty($permission->slug)) {
                $permission->slug = Str::slug($permission->name);
            }
        });
    }

    /**
     * Get the feature this permission is based on
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Get the roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    /**
     * Generate a standard permission name based on feature and action
     */
    public static function generateName($featureName, $action)
    {
        $action = ucfirst(strtolower($action));
        return "{$action} {$featureName}";
    }

    /**
     * Generate a standard permission slug based on feature and action
     */
    public static function generateSlug($featureCode, $action)
    {
        $action = strtolower($action);
        return "{$action}-{$featureCode}";
    }
}
