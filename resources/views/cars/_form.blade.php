<div class="form-group">
    <label class="form-label">Supplier *</label>
    <select name="supplier_id" class="form-control {{ $errors->has('supplier_id') ? 'is-invalid' : '' }}" required>
        <option value="">-- Select Supplier --</option>
        @foreach($suppliers ?? [] as $supplier)
            <option value="{{ $supplier->id }}" {{ old('supplier_id', $car->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }} @if($supplier->isCompanyOwned())(Company)@else(Partner)@endif
            </option>
        @endforeach
    </select>
    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small style="color: var(--text-muted); margin-top: 4px; display: block;"><a href="{{ route('suppliers.create') }}" target="_blank" style="color: var(--gold);">+ Add new supplier</a></small>
</div>

<div class="form-group">
    <label class="form-label">Plate Number *</label>
    <input type="text" name="plate_number" class="form-control {{ $errors->has('plate_number') ? 'is-invalid' : '' }}"
           value="{{ old('plate_number', $car->plate_number ?? '') }}" placeholder="e.g. ABC 1234" required>
    @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control {{ $errors->has('brand') ? 'is-invalid' : '' }}"
               value="{{ old('brand', $car->brand ?? '') }}" placeholder="e.g. Toyota">
        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control {{ $errors->has('model') ? 'is-invalid' : '' }}"
               value="{{ old('model', $car->model ?? '') }}" placeholder="e.g. Vios">
        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Year</label>
        <input type="number" name="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
               value="{{ old('year', $car->year ?? '') }}" placeholder="e.g. 2022">
        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Daily Rate (&#8369;)</label>
        <input type="number" step="0.01" name="daily_rate" class="form-control {{ $errors->has('daily_rate') ? 'is-invalid' : '' }}"
               value="{{ old('daily_rate', $car->daily_rate ?? '') }}" placeholder="e.g. 1500.00">
        @error('daily_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Vehicle Type</label>
        <select name="vehicle_type" class="form-control {{ $errors->has('vehicle_type') ? 'is-invalid' : '' }}">
            <option value="">-- Select Type --</option>
            @foreach(['sedan','suv','mpv','pickup','van','other'] as $t)
                <option value="{{ $t }}" {{ old('vehicle_type', $car->vehicle_type ?? '') == $t ? 'selected' : '' }}>
                    {{ ucfirst($t) }}
                </option>
            @endforeach
        </select>
        @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Transmission</label>
        <select name="transmission" class="form-control {{ $errors->has('transmission') ? 'is-invalid' : '' }}">
            <option value="">-- Select --</option>
            <option value="automatic" {{ old('transmission', $car->transmission ?? '') == 'automatic' ? 'selected' : '' }}>Automatic</option>
            <option value="manual" {{ old('transmission', $car->transmission ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
        </select>
        @error('transmission')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group">
        <label class="form-label">Fuel Type</label>
        <select name="fuel_type" class="form-control {{ $errors->has('fuel_type') ? 'is-invalid' : '' }}">
            <option value="">-- Select --</option>
            <option value="gasoline" {{ old('fuel_type', $car->fuel_type ?? '') == 'gasoline' ? 'selected' : '' }}>Gasoline</option>
            <option value="diesel" {{ old('fuel_type', $car->fuel_type ?? '') == 'diesel' ? 'selected' : '' }}>Diesel</option>
            <option value="electric" {{ old('fuel_type', $car->fuel_type ?? '') == 'electric' ? 'selected' : '' }}>Electric</option>
        </select>
        @error('fuel_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Seating Capacity</label>
        <input type="number" name="seating_capacity" class="form-control {{ $errors->has('seating_capacity') ? 'is-invalid' : '' }}"
               value="{{ old('seating_capacity', $car->seating_capacity ?? '') }}" placeholder="e.g. 5" min="2" max="20">
        @error('seating_capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-group">
    <label class="form-label">Status</label>
    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="">-- Select Status --</option>
        @foreach(['available','rented','maintenance'] as $s)
            <option value="{{ $s }}" {{ old('status', $car->status ?? '') == $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-group">
    <label class="form-label">Vehicle Image</label>
    <input type="file" name="image" class="form-control-file" accept="image/*">
    @if(isset($car) && $car->image_path)
        <small style="color: var(--text-muted); margin-top: 4px; display: block;">Current image uploaded. Upload a new one to replace.</small>
    @endif
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-group">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2" placeholder="Optional vehicle description...">{{ old('description', $car->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>