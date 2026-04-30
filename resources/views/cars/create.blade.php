@extends('layouts.app')

@section('title', 'Add Car')
@section('page-title', 'Add New Car')
@section('breadcrumb', 'Add a vehicle to your fleet')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('cars._form')
                <div class="form-actions">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-gold">Save Car</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection