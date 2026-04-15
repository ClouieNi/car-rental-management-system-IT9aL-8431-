@extends('layouts.app')
@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">About This System</div>
    <div class="card-body">

        <h3 style="font-size:1.1rem; margin-bottom:6px;">Cars ni Bai</h3>
        <p style="color:#6b7280; font-size:0.9rem; margin-bottom:20px;">
            A Car Rental Management System developed for Cars ni Bai, a car rental service provider
            based in Davao City that offers affordable and convenient transportation solutions for
            locals and travelers. The company provides well-maintained vehicles including Sedans,
            Compact SUVs, MPVs, and Pickup Trucks for short-term and long-term self-drive rentals.
        </p>

        <hr style="border:none; border-top:1px solid #e5e7eb; margin-bottom:20px;">

        <p style="font-size:0.9rem; margin-bottom:16px;">
            This system was built to address the operational challenges of managing a car rental
            business manually — such as scheduling conflicts, double bookings, and lack of
            centralized record-keeping. It provides a structured digital solution for fleet and
            rental transaction management.
        </p>

        <p style="font-weight:600; font-size:0.9rem; margin-bottom:8px;">Modules:</p>
        <ul style="padding-left:20px; font-size:0.9rem; color:#374151; margin-bottom:20px;">
            <li style="margin-bottom:6px;"><strong>Cars</strong> — Record and monitor incoming vehicles, update fleet inventory, and track vehicle status (Available, Rented, Maintenance).</li>
            <li><strong>Rentals</strong> — Record rental bookings, rental duration, and payment status for accurate transaction management.</li>
        </ul>

        <hr style="border:none; border-top:1px solid #e5e7eb; margin-bottom:16px;">

        <p style="font-size:0.85rem; color:#6b7280; margin-bottom:4px;">
            <strong>Built with:</strong> Laravel, CSS, MySQL
        </p>
        <p style="font-size:0.85rem; color:#6b7280; margin-bottom:4px;">
            <strong>Subject:</strong> IT9aL(8431) - Web Application Development
        </p>
        <p style="font-size:0.85rem; color:#6b7280;">
            <strong>Submitted by:</strong> Carl Louise C. Lasap
        </p>

    </div>
</div>
@endsection