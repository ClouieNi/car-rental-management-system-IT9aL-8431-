<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'customer_user_id', 'customer_name',
        'rental_type', 'destination', 'distance_km',
        'distance_surcharge', 'driver_license_path',
        'start_date', 'end_date', 'total_cost',
        'payment_status', 'amount_paid', 'status',
        'customer_notes', 'admin_notes',
        'contract_signed', 'contract_signed_at',
    ];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'total_cost'         => 'decimal:2',
        'distance_surcharge' => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'contract_signed'    => 'boolean',
        'contract_signed_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function messages()
    {
        return $this->hasMany(CustomerMessage::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['reserved', 'ongoing']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForCustomer($query, $userId)
    {
        return $query->where('customer_user_id', $userId);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) ?: 1;
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->total_cost - (float) $this->amount_paid;
    }

    public function getRentalIdDisplayAttribute(): string
    {
        return 'R' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'reserved'  => 'info',
            'ongoing'   => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'    => 'success',
            'partial' => 'warning',
            'unpaid'  => 'danger',
            default   => 'secondary',
        };
    }

    public static function calculateDistanceSurcharge(int $distanceKm): float
    {
        if ($distanceKm <= 0) return 0.0;
        $increments = floor($distanceKm / 20);
        return $increments * 100;
    }
}