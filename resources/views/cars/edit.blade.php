@extends('layouts.app')
@section('content')
<div style="max-width:560px; margin:0 auto;">
    <div class="card">
        <div class="card-header">Edit Car — {{ $car->plate_number }}</div>
        <div class="card-body">
            <form action="{{ route('cars.update', $car) }}" method="POST">
                @csrf @method('PUT')
                @include('cars._form')
                <div class="form-actions">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-warning">Update Car</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection