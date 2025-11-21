<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'role',
        'permissions',
        'is_active',
        'invited_at',
        'joined_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'invited_at' => 'datetime',
        'joined_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'owner') {
            return true;
        }

        $rolePermissions = $this->getRolePermissions();
        $customPermissions = $this->permissions ?? [];

        return in_array($permission, array_merge($rolePermissions, $customPermissions));
    }

    public function getRolePermissions(): array
    {
        return match($this->role) {
            'owner' => ['*'], // All permissions
            'admin' => [
                'manage_members',
                'manage_projects',
                'manage_translations',
                'view_analytics',
                'manage_billing',
            ],
            'manager' => [
                'manage_projects',
                'manage_translations',
                'view_analytics',
            ],
            'member' => [
                'create_translations',
                'view_projects',
            ],
            'viewer' => [
                'view_projects',
                'view_translations',
            ],
            default => [],
        };
    }

    public function getRoleTextAttribute()
    {
        return match($this->role) {
            'owner' => 'المالك',
            'admin' => 'مدير',
            'manager' => 'مشرف',
            'member' => 'عضو',
            'viewer' => 'مشاهد',
            default => $this->role,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
