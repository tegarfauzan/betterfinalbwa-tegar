<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    //  Install Laravel Sanctum
    // HasApiTokens akan bisa bikin bagian 'auth:sanctum' pada Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    // Install Spatie Permission
    // HasRoles akan bisa bikin bagian 'role:manager' pada Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone',
        'gender',
    ];

    public function bookingTransactions()
    {
        return $this->hasMany(BookingTransaction::class);
    }

    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null; // No image available
        }

        return url(Storage::url($value));
    }

    /**
     * The attributes that should be hidden for serialization (diubah jadi array/JSON).
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
            // verifikasi email di kirim ke pengguna
            'email_verified_at' => 'datetime',
            // Dengan cast hashed, tidak perlu lagi manual Hash::make() saat assign password. Laravel otomatis hash-kan.
            //Kalau pakai keduanya bersamaan, password akan di-hash dua kali — ini bug! Pastikan pilih salah satu, makanya ku komentar.
            // 'password' => 'hashed',
        ];
    }
}
