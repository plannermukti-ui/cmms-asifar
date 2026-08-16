<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles {
        HasRoles::hasPermissionTo as protected hasRoleOrDirectPermissionTo;
    }
    use LogsActivity, SoftDeletes;

    /**
     * Permission langsung menjadi prioritas ketika user memilikinya.
     *
     * Dengan demikian, "Spesifik Hak Akses" dapat membatasi akses role,
     * bukan hanya menambahkannya seperti perilaku default Spatie Permission.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // Super Admin selalu memiliki seluruh hak akses, walau ada permission
        // spesifik lama yang tersimpan pada akun tersebut.
        if ($this->hasRole('Super Admin')) {
            return true;
        }

        if ($this->getDirectPermissions()->isNotEmpty()) {
            $permission = $this->filterPermission($permission, $guardName);

            return $this->hasDirectPermission($permission);
        }

        return $this->hasRoleOrDirectPermissionTo($permission, $guardName);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_lengkap', 'email', 'status', 'role_id', 'department_id', 'jabatan_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'email',
        'avatar',
        'password',
        'no_whatsapp',
        'bio',
        'department_id',
        'jabatan_id',
        'role_id',
        'site_id',
        'status',
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
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists('avatars/' . $this->avatar)) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return null;
    }
}
