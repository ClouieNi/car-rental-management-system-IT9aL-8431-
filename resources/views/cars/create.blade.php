@extends('layouts.app')
@section('content')
<div style="max-width:560px; margin:0 auto;">
    <div class="card">
        <div class="card-header">Add New Car</div>
        <div class="card-body">
            <form action="{{ route('cars.store') }}" method="POST">
                @csrf
                @include('cars._form')
                <div class="form-actions">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-primary">Save Car</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection