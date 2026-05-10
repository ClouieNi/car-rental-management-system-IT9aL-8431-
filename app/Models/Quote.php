<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name', 'guest_email', 'guest_phone',
        'user_id', 'car_id',
        'start_date', 'end_date', 'days',
        'rental_type', 'destination', 'distance_km',
        'base_cost', 'distance_surcharge', 'total_estimate',
        'status', 'guest_notes', 'admin_remarks',
        'license_file_path', 'rental_id', 'expires_at',
    ];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'base_cost'          => 'decimal:2',
        'distance_surcharge' => 'decimal:2',
        'total_estimate'     => 'decimal:2',
        'expires_at'         => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function getQuoteIdDisplayAttribute(): string
    {
        return 'Q' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}