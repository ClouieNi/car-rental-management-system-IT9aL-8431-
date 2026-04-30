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
        'distance_surcharge', 'driver_license_path', 'driver_id',
        'start_date', 'end_date', 'total_cost',
        'payment_status', 'amount_paid', 'status',
        'customer_notes', 'admin_notes',
        'contract_signed', 'contract_signed_at',
        'contract_file_path', 'id_file_path',
        'contract_status', 'id_status',
        'contract_verified_at', 'contract_verified_by',
        'id_verified_at', 'id_verified_by',
        'vehicle_released_at', 'released_by',
        'cancellation_reason', 'cancellation_requested_at',
        'cancellation_refund_percent', 'refund_amount',
    ];

    protected $casts = [
        'start_date'                 => 'date',
        'end_date'                   => 'date',
        'total_cost'                 => 'decimal:2',
        'distance_surcharge'         => 'decimal:2',
        'amount_paid'                => 'decimal:2',
        'contract_signed'            => 'boolean',
        'contract_signed_at'         => 'datetime',
        'contract_verified_at'       => 'datetime',
        'id_verified_at'             => 'datetime',
        'vehicle_released_at'        => 'datetime',
        'cancellation_requested_at'  => 'datetime',
        'cancellation_refund_percent' => 'decimal:2',
        'refund_amount'              => 'decimal:2',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function messages()
    {
        return $this->hasMany(CustomerMessage::class);
    }

    public function rentalReturn()
    {
        return $this->hasOne(RentalReturn::class);
    }

    public function contractVerifiedBy()
    {
        return $this->belongsTo(User::class, 'contract_verified_by');
    }

    public function idVerifiedBy()
    {
        return $this->belongsTo(User::class, 'id_verified_by');
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved', 'documents_pending', 'documents_verified', 'reserved', 'ongoing', 'return_pending']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDocumentsPending($query)
    {
        return $query->where('status', 'documents_pending');
    }

    public function scopeDocumentsVerified($query)
    {
        return $query->where('status', 'documents_verified');
    }

    public function scopeReturnPending($query)
    {
        return $query->where('status', 'return_pending');
    }

    public function scopeCancellationRequested($query)
    {
        return $query->where('status', 'cancellation_requested');
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
            'pending'             => 'warning',
            'approved'            => 'info',
            'documents_pending'   => 'warning',
            'documents_verified'  => 'info',
            'reserved'            => 'info',
            'ongoing'             => 'success',
            'return_pending'      => 'warning',
            'completed'           => 'secondary',
            'cancelled'           => 'danger',
            'cancellation_requested' => 'danger',
            default               => 'secondary',
        };
    }

    public function getDocumentStatusColorAttribute(): string
    {
        return match ($this->contract_status) {
            'verified'  => 'success',
            'uploaded'  => 'info',
            'rejected'  => 'danger',
            default     => 'warning',
        };
    }

    public function getIdStatusColorAttribute(): string
    {
        return match ($this->id_status) {
            'verified'  => 'success',
            'uploaded'  => 'info',
            'rejected'  => 'danger',
            default     => 'warning',
        };
    }

    public function isDocumentsComplete(): bool
    {
        return $this->contract_status === 'verified' && $this->id_status === 'verified';
    }

    public function canBeReleased(): bool
    {
        return in_array($this->status, ['documents_verified', 'reserved']) 
            && $this->isDocumentsComplete();
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

    public function calculateRefundPercent(): float
    {
        $daysUntilRental = now()->diffInDays($this->start_date);
        
        if ($daysUntilRental >= 7) return 0.80;
        if ($daysUntilRental >= 3) return 0.50;
        return 0.00;
    }

    public function calculateRefundAmount(): float
    {
        $percent = $this->calculateRefundPercent();
        return round($this->total_cost * $percent, 2);
    }

    public function getTotalDamageFee(): float
    {
        return $this->rentalReturn?->damage_fee ?? 0.0;
    }

    public function getTotalAdditionalCharges(): float
    {
        return $this->rentalReturn?->total_additional_charges ?? 0.0;
    }

    public function getFinalTotal(): float
    {
        $damage = $this->getTotalDamageFee();
        $additional = $this->getTotalAdditionalCharges();
        return $this->total_cost + $damage + $additional;
    }
}