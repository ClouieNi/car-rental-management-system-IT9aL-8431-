@extends('layouts.app')
@section('content')
<div style="max-width:560px; margin:0 auto;">
    <div class="card">
        <div class="card-header">Edit Rental — {{ $rental->customer_name }}</div>
        <div class="card-body">
            <form action="{{ route('rentals.update', $rental) }}" method="POST">
                @csrf @method('PUT')
                @include('rentals._form')
                <div class="form-actions">
                    <a href="{{ route('rentals.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-warning">Update Rental</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection