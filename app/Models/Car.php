<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['plate_number', 'brand', 'model', 'year', 'daily_rate', 'status'];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}