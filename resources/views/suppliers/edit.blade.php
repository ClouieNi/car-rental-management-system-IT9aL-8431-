@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')
@section('breadcrumb', 'Update supplier details')

@section('content')

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
        @csrf @method('PUT')
        @include('suppliers._form')
        <div class="form-actions">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-gold">Update Supplier</button>
        </div>
    </form>
</div>

@endsection
