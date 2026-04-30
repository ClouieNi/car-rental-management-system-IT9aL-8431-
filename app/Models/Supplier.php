<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'contact_person', 'phone', 'email',
        'commission_rate', 'address', 'notes', 'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCompanyOwned($query)
    {
        return $query->where('type', 'company-owned');
    }

    public function scopePartnerOwned($query)
    {
        return $query->where('type', 'partner-owned');
    }

    public function isCompanyOwned(): bool
    {
        return $this->type === 'company-owned';
    }

    public function isPartnerOwned(): bool
    {
        return $this->type === 'partner-owned';
    }

    public function getCommissionRateDisplayAttribute(): string
    {
        if ($this->isCompanyOwned()) {
            return 'N/A (Company)';
        }
        return $this->commission_rate ? $this->commission_rate . '%' : 'Not set';
    }

    public function calculateCommission(float $amount): float
    {
        if ($this->isCompanyOwned() || !$this->commission_rate) {
            return 0.0;
        }
        return round($amount * ($this->commission_rate / 100), 2);
    }
}
