<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'brand', 'model', 'year',
        'vehicle_type', 'transmission', 'fuel_type',
        'seating_capacity', 'daily_rate', 'status',
        'image_path', 'description',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'year'       => 'integer',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function activeRental()
    {
        return $this->hasOne(Rental::class)->whereIn('status', ['reserved', 'ongoing']);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/car-placeholder.png');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'success',
            'rented'      => 'warning',
            'maintenance' => 'danger',
            default       => 'secondary',
        };
    }

    public function isAvailableForDates(string $startDate, string $endDate): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        return !$this->rentals()
            ->whereIn('status', ['reserved', 'ongoing'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            })
            ->exists();
    }
}