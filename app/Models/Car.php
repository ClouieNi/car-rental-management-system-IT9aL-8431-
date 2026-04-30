<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'plate_number', 'brand', 'model', 'year',
        'vehicle_type', 'transmission', 'fuel_type',
        'seating_capacity', 'daily_rate', 'status',
        'image_path', 'description',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'year'       => 'integer',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

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

    public function scopeAvailableBetween($query, $startDate, $endDate)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('rentals', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['reserved', 'approved', 'ongoing'])
                    ->where(function ($subQ) use ($startDate, $endDate) {
                        $subQ->whereBetween('start_date', [$startDate, $endDate])
                             ->orWhereBetween('end_date', [$startDate, $endDate])
                             ->orWhere(function ($subQ2) use ($startDate, $endDate) {
                                 $subQ2->where('start_date', '<=', $startDate)
                                       ->where('end_date', '>=', $endDate);
                             });
                    });
            });
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
        $typeImages = ['sedan', 'suv', 'mpv', 'pickup'];
        $type = in_array($this->vehicle_type, $typeImages) ? $this->vehicle_type : 'sedan';
        return asset("images/{$type}.png");
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

    public function getOwnershipBadgeAttribute(): string
    {
        if (!$this->supplier) {
            return '<span class="badge badge-secondary">No Supplier</span>';
        }
        if ($this->supplier->isCompanyOwned()) {
            return '<span class="badge badge-success">Company</span>';
        }
        return '<span class="badge badge-info">Partner</span>';
    }

    public function scopeWithSupplier($query)
    {
        return $query->with('supplier');
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