@extends('layouts.app')

@section('title', 'Edit Car')
@section('page-title', 'Edit Vehicle')
@section('breadcrumb', 'Update vehicle details')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cars.update', $car) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('cars._form')
                <div class="form-actions">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-gold">Update Car</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection