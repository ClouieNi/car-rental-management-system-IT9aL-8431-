<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Suppliers ───────────────────────────────────────
        $company1 = Supplier::firstOrCreate(
            ['name' => 'Cars ni Bai Fleet'],
            [
                'type'           => 'company-owned',
                'contact_person' => 'Carl',
                'phone'          => '0917-123-4567',
                'email'          => 'carl@carsnibai.com',
                'address'        => 'Davao City, Philippines',
                'notes'          => 'Primary company fleet',
                'is_active'      => true,
            ]
        );

        $company2 = Supplier::firstOrCreate(
            ['name' => 'CNB Auto Pool'],
            [
                'type'           => 'company-owned',
                'contact_person' => 'Marco',
                'phone'          => '0918-987-6543',
                'email'          => 'marco@carsnibai.com',
                'address'        => 'General Santos City, Philippines',
                'notes'          => 'Secondary company fleet',
                'is_active'      => true,
            ]
        );

        $partner = Supplier::firstOrCreate(
            ['name' => 'Juan Dela Cruz Motors'],
            [
                'type'            => 'partner-owned',
                'commission_rate' => 20.00,
                'contact_person'  => 'Juan Dela Cruz',
                'phone'           => '0919-555-1234',
                'email'           => 'juan@delacruzmotors.com',
                'address'         => 'Tagum City, Philippines',
                'notes'           => 'Partner consignor — 20% commission per rental',
                'is_active'       => true,
            ]
        );

        // ── Cars ────────────────────────────────────────────
        Car::firstOrCreate(
            ['plate_number' => 'ABC-1234'],
            [
                'supplier_id'      => $company1->id,
                'brand'            => 'Toyota',
                'model'            => 'Vios',
                'year'             => 2022,
                'vehicle_type'     => 'sedan',
                'transmission'     => 'automatic',
                'fuel_type'        => 'gasoline',
                'seating_capacity' => 5,
                'daily_rate'       => 1500.00,
                'status'           => 'available',
                'description'      => 'Fuel-efficient sedan perfect for city driving.',
            ]
        );

        Car::firstOrCreate(
            ['plate_number' => 'XYZ-5678'],
            [
                'supplier_id'      => $company1->id,
                'brand'            => 'Toyota',
                'model'            => 'Fortuner',
                'year'             => 2023,
                'vehicle_type'     => 'suv',
                'transmission'     => 'automatic',
                'fuel_type'        => 'diesel',
                'seating_capacity' => 7,
                'daily_rate'       => 3500.00,
                'status'           => 'available',
                'description'      => 'Powerful SUV for long-distance travel and rough terrain.',
            ]
        );

        Car::firstOrCreate(
            ['plate_number' => 'DEF-9012'],
            [
                'supplier_id'      => $company2->id,
                'brand'            => 'Toyota',
                'model'            => 'Innova',
                'year'             => 2021,
                'vehicle_type'     => 'mpv',
                'transmission'     => 'automatic',
                'fuel_type'        => 'diesel',
                'seating_capacity' => 8,
                'daily_rate'       => 2500.00,
                'status'           => 'available',
                'description'      => 'Spacious MPV ideal for family and group travel.',
            ]
        );

        Car::firstOrCreate(
            ['plate_number' => 'GHI-3456'],
            [
                'supplier_id'      => $partner->id,
                'brand'            => 'Ford',
                'model'            => 'Ranger',
                'year'             => 2023,
                'vehicle_type'     => 'pickup',
                'transmission'     => 'manual',
                'fuel_type'        => 'diesel',
                'seating_capacity' => 5,
                'daily_rate'       => 2800.00,
                'status'           => 'available',
                'description'      => 'Rugged pickup truck — partner-owned (20% commission).',
            ]
        );

        $this->command->info('Demo data seeded: 3 suppliers, 4 cars.');
    }
}
