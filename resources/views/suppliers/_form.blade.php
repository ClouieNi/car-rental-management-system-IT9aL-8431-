<div class="form-group">
    <label>Supplier Name *</label>
    <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" 
           class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label>Type *</label>
    <select name="type" id="supplier-type" required>
        <option value="">-- Select Type --</option>
        <option value="company-owned" {{ old('type', $supplier->type ?? '') == 'company-owned' ? 'selected' : '' }}>
            Company Owned
        </option>
        <option value="partner-owned" {{ old('type', $supplier->type ?? '') == 'partner-owned' ? 'selected' : '' }}>
            Partner Owned
        </option>
    </select>
    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-group" id="commission-group">
    <label>Commission Rate (%)</label>
    <input type="number" name="commission_rate" step="0.01" min="0" max="100"
           value="{{ old('commission_rate', $supplier->commission_rate ?? '') }}"
           class="{{ $errors->has('commission_rate') ? 'is-invalid' : '' }}"
           placeholder="e.g., 15 for 15%">
    @error('commission_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small style="color: var(--text-muted);">Only applies to partner-owned vehicles</small>
</div>

<div class="form-row">
    <div class="form-group">
        <label>Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}">
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}">
</div>

<div class="form-group">
    <label>Address</label>
    <textarea name="address" rows="2">{{ old('address', $supplier->address ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Notes</label>
    <textarea name="notes" rows="3">{{ old('notes', $supplier->notes ?? '') }}</textarea>
</div>

<div class="form-group">
    <label class="checkbox-label">
        <input type="checkbox" name="is_active" value="1" 
            {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
        Active Supplier
    </label>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('supplier-type');
    const commissionGroup = document.getElementById('commission-group');
    
    function toggleCommission() {
        if (typeSelect.value === 'company-owned') {
            commissionGroup.style.opacity = '0.5';
            commissionGroup.querySelector('input').disabled = true;
        } else {
            commissionGroup.style.opacity = '1';
            commissionGroup.querySelector('input').disabled = false;
        }
    }
    
    typeSelect.addEventListener('change', toggleCommission);
    toggleCommission();
});
</script>
@endpush
