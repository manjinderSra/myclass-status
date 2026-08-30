<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'role',
        'school_id',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the school that the user belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the school that this user administers
     */
    public function administeredSchool()
    {
        return $this->hasOne(School::class, 'admin_id');
    }

    /**
     * Get the roles assigned to this user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * Check if the user has a specific role
     */
    public function hasRole($role)
    {
        // Initialize roles collection if null
        if (!$this->roles) {
            return false;
        }
        
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }
        
        return $this->roles->contains($role);
    }

    /**
     * Check if the user has a specific permission through any of their roles
     */
    public function hasPermission($permission)
    {
        // Initialize roles collection if null
        if (!$this->roles) {
            return false;
        }
        
        $permissionSlug = is_string($permission) ? $permission : $permission->slug;
        
        return $this->roles->reduce(function($hasPermission, $role) use ($permissionSlug) {
            return $hasPermission || $role->permissions->contains('slug', $permissionSlug);
        }, false);
    }

    /**
     * Assign roles to this user
     */
    public function assignRoles($roles)
    {
        try {
            if (is_string($roles)) {
                $roles = Role::where('slug', $roles)->get();
            }
            
            if ($roles instanceof Role) {
                $roles = collect([$roles]);
            }
            
            if (!is_null($roles) && count($roles) > 0) {
                $roleIds = $roles->pluck('id')->toArray();
                $this->roles()->syncWithoutDetaching($roleIds);
            }
            
            return $this;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error assigning roles: ' . $e->getMessage(), [
                'user_id' => $this->id,
                'roles' => $roles
            ]);
            return $this;
        }
    }

    /**
     * Remove roles from this user
     */
    public function removeRoles($roles)
    {
        if (is_string($roles)) {
            $roles = Role::where('slug', $roles)->get();
        }
        
        if ($roles instanceof Role) {
            $roles = collect([$roles]);
        }
        
        $this->roles()->detach($roles);
        
        return $this;
    }
    
    /**
     * Generate a random username
     */
    public static function generateUsername($firstName, $lastName)
    {
        $baseUsername = strtolower(substr($firstName, 0, 1) . $lastName);
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        $username = $baseUsername;
        $counter = 1;
        
        // Check if username exists
        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
    
    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
        
        return $this;
    }
}
