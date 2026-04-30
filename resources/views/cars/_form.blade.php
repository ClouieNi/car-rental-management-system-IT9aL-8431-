<div class="form-group">
    <label>Supplier *</label>
    <select name="supplier_id" class="{{ $errors->has('supplier_id') ? 'is-invalid' : '' }}" required>
        <option value="">-- Select Supplier --</option>
        @foreach($suppliers ?? [] as $supplier)
            <option value="{{ $supplier->id }}" {{ old('supplier_id', $car->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }} @if($supplier->isCompanyOwned())(Company)@else(Partner)@endif
            </option>
        @endforeach
    </select>
    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small style="color: var(--text-muted);"><a href="{{ route('suppliers.create') }}" target="_blank">+ Add new supplier</a></small>
</div>

<div class="form-group">
    <label>Plate Number *</label>
    <input type="text" name="plate_number" class="{{ $errors->has('plate_number') ? 'is-invalid' : '' }}"
           value="{{ old('plate_number', $car->plate_number ?? '') }}" placeholder="e.g. ABC 1234" required>
    @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-row">
    <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" class="{{ $errors->has('brand') ? 'is-invalid' : '' }}"
               value="{{ old('brand', $car->brand ?? '') }}" placeholder="e.g. Toyota">
        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label>Model</label>
        <input type="text" name="model" class="{{ $errors->has('model') ? 'is-invalid' : '' }}"
               value="{{ old('model', $car->model ?? '') }}" placeholder="e.g. Vios">
        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label>Year</label>
        <input type="number" name="year" class="{{ $errors->has('year') ? 'is-invalid' : '' }}"
               value="{{ old('year', $car->year ?? '') }}" placeholder="e.g. 2022">
        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label>Daily Rate (&#8369;)</label>
        <input type="number" step="0.01" name="daily_rate" class="{{ $errors->has('daily_rate') ? 'is-invalid' : '' }}"
               value="{{ old('daily_rate', $car->daily_rate ?? '') }}" placeholder="e.g. 1500.00">
        @error('daily_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-group">
    <label>Status</label>
    <select name="status" class="{{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="">-- Select Status --</option>
        @foreach(['available','rented','maintenance'] as $s)
            <option value="{{ $s }}" {{ old('status', $car->status ?? '') == $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>