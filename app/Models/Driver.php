<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry',
        'license_file_path',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifications()
    {
        return $this->hasMany(DriverVerification::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function isLicenseExpired(): bool
    {
        return now()->isAfter($this->license_expiry);
    }
}
