<div class="form-group">
    <label class="form-label">Customer Name</label>
    <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
           value="{{ old('customer_name', $rental->customer_name ?? '') }}" placeholder="e.g. Juan Dela Cruz">
    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-group">
    <label class="form-label">Car</label>
    <select name="car_id" class="form-control {{ $errors->has('car_id') ? 'is-invalid' : '' }}">
        <option value="">-- Select Car --</option>
        @foreach($cars as $car)
            <option value="{{ $car->id }}"
                {{ old('car_id', $rental->car_id ?? '') == $car->id ? 'selected' : '' }}>
                {{ $car->brand }} {{ $car->model }} — {{ $car->plate_number }}
                (&#8369;{{ number_format($car->daily_rate, 2) }}/day)
            </option>
        @endforeach
    </select>
    @error('car_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Rental Type</label>
        <select name="rental_type" class="form-control {{ $errors->has('rental_type') ? 'is-invalid' : '' }}">
            <option value="self_drive" {{ old('rental_type', $rental->rental_type ?? '') == 'self_drive' ? 'selected' : '' }}>Self Drive</option>
            <option value="with_driver" {{ old('rental_type', $rental->rental_type ?? '') == 'with_driver' ? 'selected' : '' }}>With Driver</option>
        </select>
        @error('rental_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}">
            <option value="unpaid" {{ old('payment_status', $rental->payment_status ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="partial" {{ old('payment_status', $rental->payment_status ?? '') == 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="paid" {{ old('payment_status', $rental->payment_status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
@isset($rental->id)
<div class="form-group">
    <label class="form-label">Status</label>
    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        @foreach(['reserved','ongoing','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ old('status', $rental->status ?? '') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
@endisset
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Start Date</label>
        <input type="text" name="start_date" class="form-control datepicker {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
               value="{{ old('start_date', $rental->start_date ?? '') }}" placeholder="Select date">
        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input type="text" name="end_date" class="form-control datepicker {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
               value="{{ old('end_date', $rental->end_date ?? '') }}" placeholder="Select date">
        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Destination</label>
        <input type="text" name="destination" class="form-control"
               value="{{ old('destination', $rental->destination ?? '') }}" placeholder="e.g. Cebu City">
    </div>
    <div class="form-group">
        <label class="form-label">Distance (km)</label>
        <input type="number" name="distance_km" class="form-control" min="0"
               value="{{ old('distance_km', $rental->distance_km ?? '') }}" placeholder="0">
    </div>
</div>
<div class="form-group">
    <label class="form-label">Amount Paid (&#8369;)</label>
    <input type="number" step="0.01" name="amount_paid" class="form-control" min="0"
           value="{{ old('amount_paid', $rental->amount_paid ?? '0') }}" placeholder="0.00">
</div>
<div class="form-group">
    <label class="form-label">Customer Notes</label>
    <textarea name="customer_notes" class="form-control" rows="2" placeholder="Any notes from the customer...">{{ old('customer_notes', $rental->customer_notes ?? '') }}</textarea>
</div>
<div class="form-group">
    <label class="form-label">Admin Notes</label>
    <textarea name="admin_notes" class="form-control" rows="2" placeholder="Internal notes...">{{ old('admin_notes', $rental->admin_notes ?? '') }}</textarea>
</div>