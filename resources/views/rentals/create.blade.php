@extends('layouts.app')

@section('title', 'New Rental')
@section('page-title', 'New Rental')
@section('breadcrumb', 'Create a new rental transaction')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('rentals.store') }}" method="POST">
                @csrf
                @include('rentals._form')
                <div class="form-actions">
                    <a href="{{ route('rentals.index') }}" class="btn btn-secondary">Cancel</a>
                    <button class="btn btn-gold">Save Rental</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection