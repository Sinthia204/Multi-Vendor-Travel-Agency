<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agency extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'description',
        'password',
        'logo_path',
        'status',
        'registered_at',
        'approved_at',
        'rejected_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'registered_at' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
