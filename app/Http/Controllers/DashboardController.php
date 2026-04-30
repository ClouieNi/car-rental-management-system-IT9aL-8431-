<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CustomerMessage;
use App\Models\Quote;
use App\Models\Rental;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_cars'       => Car::count(),
            'available_cars'   => Car::where('status', 'available')->count(),
            'rented_cars'      => Car::where('status', 'rented')->count(),
            'maintenance_cars' => Car::where('status', 'maintenance')->count(),
            'active_rentals'   => Rental::active()->count(),
            'pending_approvals' => Rental::pending()->count(),
            'total_revenue'    => Rental::where('payment_status', 'paid')->sum('total_cost'),
            'pending_balance'  => Rental::whereIn('status', ['reserved', 'approved', 'ongoing'])
                                        ->selectRaw('SUM(total_cost - amount_paid) as balance')
                                        ->value('balance') ?? 0,
            'pending_quotes'   => Quote::pending()->count(),
            'unread_messages'  => CustomerMessage::where('is_read', false)->count(),
            'completed_this_month' => Rental::where('status', 'completed')
                                            ->whereMonth('updated_at', now()->month)
                                            ->whereYear('updated_at', now()->year)
                                            ->count(),
            'fleet_by_type'    => Car::selectRaw('vehicle_type as label, count(*) as count')
                                     ->groupBy('vehicle_type')
                                     ->get()
                                     ->map(fn($r) => [
                                         'label' => ucfirst($r->label),
                                         'count' => $r->count,
                                     ]),
        ];

        $recentRentals = Rental::with('car')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $pendingApprovals = Rental::pending()
            ->with('car', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentRentals', 'pendingApprovals'));
    }
}