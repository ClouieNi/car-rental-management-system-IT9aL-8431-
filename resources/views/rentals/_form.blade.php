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
<div class="form-group">
    <label class="form-label">Status</label>
    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="">-- Select Status --</option>
        @foreach(['ongoing','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ old('status', $rental->status ?? '') == $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>