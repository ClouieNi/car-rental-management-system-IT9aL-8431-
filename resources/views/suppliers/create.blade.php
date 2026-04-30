@extends('layouts.app')

@section('title', 'New Supplier')
@section('page-title', 'Add Supplier')
@section('breadcrumb', 'Create new vehicle supplier')

@section('content')

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        @include('suppliers._form')
        <div class="form-actions">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Supplier</button>
        </div>
    </form>
</div>

@endsection
