<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'school_id',
        'is_system_role'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    /**
     * Get the school that this role belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the permissions assigned to this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    /**
     * Get the users assigned to this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * Check if this role has a specific permission
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('slug', $permission);
        }
        
        return $this->permissions->contains($permission);
    }

    /**
     * Assign permissions to this role
     */
    public function assignPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = Permission::where('slug', $permissions)->get();
        }

        if ($permissions instanceof Permission) {
            $permissions = collect([$permissions]);
        }

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    /**
     * Remove permissions from this role
     */
    public function removePermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = Permission::where('slug', $permissions)->get();
        }

        if ($permissions instanceof Permission) {
            $permissions = collect([$permissions]);
        }

        $this->permissions()->detach($permissions);

        return $this;
    }
}
