<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id', 'returned_at', 'returned_by',
        'damage_panels', 'damage_description', 'damage_photos',
        'damage_fee', 'damage_rate_per_panel',
        'fuel_level', 'mileage_returned', 'mileage_start',
        'fuel_charge', 'late_return_charge', 'cleaning_charge',
        'other_charges', 'other_charges_notes',
        'total_additional_charges', 'notes',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'damage_photos' => 'array',
        'damage_fee' => 'decimal:2',
        'damage_rate_per_panel' => 'decimal:2',
        'fuel_charge' => 'decimal:2',
        'late_return_charge' => 'decimal:2',
        'cleaning_charge' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_additional_charges' => 'decimal:2',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function calculateDamageFee(): float
    {
        return $this->damage_panels * $this->damage_rate_per_panel;
    }

    public function calculateTotalAdditionalCharges(): float
    {
        return $this->fuel_charge + $this->late_return_charge + 
               $this->cleaning_charge + $this->other_charges;
    }

    public function updateTotals(): void
    {
        $this->damage_fee = $this->calculateDamageFee();
        $this->total_additional_charges = $this->calculateTotalAdditionalCharges();
        $this->save();
    }
}
