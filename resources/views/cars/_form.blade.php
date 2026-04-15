<div class="form-group">
    <label>Plate Number</label>
    <input type="text" name="plate_number" class="{{ $errors->has('plate_number') ? 'is-invalid' : '' }}"
           value="{{ old('plate_number', $car->plate_number ?? '') }}" placeholder="e.g. ABC 1234">
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