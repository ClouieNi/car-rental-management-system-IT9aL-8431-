<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Damage Assessment Settings
    |--------------------------------------------------------------------------
    |
    | Configure the damage fee calculation rates.
    |
    */
    'damage_per_panel' => 5000, // PHP amount per damaged panel
    
    /*
    |--------------------------------------------------------------------------
    | Commission Settings
    |--------------------------------------------------------------------------
    |
    | Default commission rate for partner-owned vehicles.
    |
    */
    'commission_default' => 15.00, // Percentage for partner vehicles
    
    /*
    |--------------------------------------------------------------------------
    | Rental Status Flow
    |--------------------------------------------------------------------------
    |
    | The standard progression of rental statuses.
    |
    */
    'status_flow' => [
        'pending' => 'Awaiting staff approval',
        'approved' => 'Approved, needs documents',
        'documents_pending' => 'Awaiting ID/contract upload',
        'documents_verified' => 'Verified, ready for pickup',
        'reserved' => 'Vehicle reserved',
        'ongoing' => 'Vehicle with customer',
        'return_pending' => 'Returned, inspection needed',
        'completed' => 'Process complete',
        'cancelled' => 'Cancelled',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Document Settings
    |--------------------------------------------------------------------------
    |
    | File upload settings for rental documents.
    |
    */
    'documents' => [
        'max_contract_size' => 10240, // KB (10MB)
        'max_id_size' => 5120, // KB (5MB)
        'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
        'storage_disk' => 'private',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Return Settings
    |--------------------------------------------------------------------------
    |
    | Settings for vehicle return processing.
    |
    */
    'return' => [
        'late_fee_per_hour' => 200, // PHP per hour
        'fuel_charge_levels' => [
            'full' => 0,
            'partial' => 500,
            'empty' => 1000,
        ],
        'cleaning_fee' => 300, // PHP if vehicle requires cleaning
    ],
];
