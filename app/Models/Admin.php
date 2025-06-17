<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_level',
        'permissions',
        'department',
        'access_level',
        'last_login_at',
        'login_count',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_login_at' => 'datetime',
        'login_count' => 'integer',
        'access_level' => 'integer'
    ];

    /**
     * Get the user that owns the admin profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who created this admin.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for super admins.
     */
    public function scopeSuperAdmins($query)
    {
        return $query->where('admin_level', 'super');
    }

    /**
     * Scope for regular admins.
     */
    public function scopeRegularAdmins($query)
    {
        return $query->where('admin_level', 'regular');
    }

    /**
     * Check if admin has specific permission.
     */
    public function hasPermission($permission)
    {
        if ($this->admin_level === 'super') {
            return true; // Super admins have all permissions
        }

        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Get admin's full name through user relationship.
     */
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->full_name : 'Unknown Admin';
    }

    /**
     * Get admin's email through user relationship.
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Get admin's phone through user relationship.
     */
    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone : null;
    }

    /**
     * Get admin's address through user relationship.
     */
    public function getAddressAttribute()
    {
        return $this->user ? $this->user->address : null;
    }

    /**
     * Get admin level display name.
     */
    public function getAdminLevelDisplayAttribute()
    {
        return match($this->admin_level) {
            'super' => 'Super Administrator',
            'regular' => 'Administrator',
            'moderator' => 'Moderator',
            default => 'Unknown Level'
        };
    }

    /**
     * Get access level display name.
     */
    public function getAccessLevelDisplayAttribute()
    {
        return match($this->access_level) {
            10 => 'Full Access',
            8 => 'High Access',
            6 => 'Medium Access',
            4 => 'Limited Access',
            2 => 'Read Only',
            default => 'No Access'
        };
    }

    /**
     * Get default permissions based on admin level.
     */
    public static function getDefaultPermissions($adminLevel)
    {
        return match($adminLevel) {
            'super' => [
                'manage_users',
                'manage_admins',
                'manage_doctors',
                'manage_patients',
                'manage_appointments',
                'manage_consultations',
                'manage_prescriptions',
                'view_statistics',
                'manage_system_settings',
                'view_logs',
                'manage_symptom_checks',
                'export_data',
                'backup_system'
            ],
            'regular' => [
                'manage_users',
                'manage_doctors',
                'manage_patients',
                'manage_appointments',
                'manage_consultations',
                'view_statistics',
                'manage_symptom_checks'
            ],
            'moderator' => [
                'view_users',
                'view_appointments',
                'view_consultations',
                'view_statistics',
                'view_symptom_checks'
            ],
            default => []
        };
    }

    /**
     * Create admin with default settings.
     */
    public static function createAdmin($userId, $adminLevel = 'regular', $createdBy = null)
    {
        return self::create([
            'user_id' => $userId,
            'admin_level' => $adminLevel,
            'permissions' => self::getDefaultPermissions($adminLevel),
            'access_level' => match($adminLevel) {
                'super' => 10,
                'regular' => 8,
                'moderator' => 6,
                default => 4
            },
            'department' => 'Administration',
            'login_count' => 0,
            'created_by' => $createdBy
        ]);
    }

    /**
     * Update login statistics.
     */
    public function updateLoginStats()
    {
        $this->update([
            'last_login_at' => now(),
            'login_count' => $this->login_count + 1
        ]);
    }

    /**
     * Get all available permissions.
     */
    public static function getAllPermissions()
    {
        return [
            'manage_users' => 'Manage Users',
            'manage_admins' => 'Manage Administrators',
            'manage_doctors' => 'Manage Doctors',
            'manage_patients' => 'Manage Patients',
            'manage_appointments' => 'Manage Appointments',
            'manage_consultations' => 'Manage Consultations',
            'manage_prescriptions' => 'Manage Prescriptions',
            'view_statistics' => 'View Statistics',
            'manage_system_settings' => 'Manage System Settings',
            'view_logs' => 'View System Logs',
            'manage_symptom_checks' => 'Manage Symptom Checks',
            'export_data' => 'Export Data',
            'backup_system' => 'Backup System',
            'view_users' => 'View Users',
            'view_appointments' => 'View Appointments',
            'view_consultations' => 'View Consultations',
            'view_symptom_checks' => 'View Symptom Checks'
        ];
    }

    /**
     * Get admin statistics.
     */
    public function getStatsAttribute()
    {
        return [
            'total_logins' => $this->login_count,
            'last_login' => $this->last_login_at ? $this->last_login_at->diffForHumans() : 'Never',
            'account_age' => $this->created_at->diffForHumans(),
            'permissions_count' => count($this->permissions ?? []),
            'access_level' => $this->access_level_display
        ];
    }
}
