<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Check if user is admin
    public function isAdmin()
    {
        return in_array($this->role, ['superadmin', 'apoteker']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    // Check if user is apoteker
    public function isApoteker()
    {
        return $this->role === 'apoteker';
    }

    // Check if user is customer
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Scope untuk role tertentu
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope untuk admin dan apoteker
    public function scopeStaff($query)
    {
        return $query->whereIn('role', ['admin', 'apoteker']);
    }

    public function canAccessMenu(string $menu): bool
    {
        // Super admin can access all menus
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Apoteker can only access certain menus
        if ($this->isApoteker()) {
            $apotekerMenus = [
                'dashboard',
                'daftar_obat',
                'transaksi',
                'kadaluarsa',
            ];

            return in_array($menu, $apotekerMenus);
        }

        return false;
    }
}
