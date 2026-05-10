@extends('layouts.app')

@section('title', 'Process Return')
@section('page-title', 'Vehicle Return')
@section('breadcrumb', 'Process vehicle return and inspection')

@push('styles')
<style>
.return-form {
    max-width: 800px;
}
.form-section {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--border-subtle);
    padding: 24px;
    margin-bottom: 24px;
}
.form-section h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-subtle);
}
.damage-counter {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}
.damage-counter button {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: var(--black-3);
    color: var(--text-primary);
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.damage-counter button:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
}
.damage-counter input {
    width: 60px;
    text-align: center;
    font-size: 24px;
    font-weight: 600;
    background: transparent;
    border: none;
    color: var(--text-primary);
}
.damage-calculator {
    background: var(--black-3);
    padding: 16px;
    border-radius: var(--radius-sm);
    margin-top: 16px;
}
.damage-calculator .row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}
.damage-calculator .total {
    border-top: 2px solid var(--border);
    margin-top: 8px;
    padding-top: 12px;
    font-weight: 600;
    font-size: 18px;
    color: var(--gold);
}
.fuel-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.fuel-option {
    position: relative;
}
.fuel-option input {
    position: absolute;
    opacity: 0;
}
.fuel-option label {
    display: block;
    padding: 12px 24px;
    background: var(--black-3);
    border: 2px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    cursor: pointer;
    text-align: center;
    transition: all 0.15s;
}
.fuel-option input:checked + label {
    border-color: var(--gold);
    background: var(--gold-muted);
    color: var(--gold);
}
.charge-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 16px;
    align-items: end;
    margin-bottom: 12px;
}
.return-form input[type="text"],
.return-form input[type="number"],
.return-form textarea {
    background: var(--black-3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    padding: 8px 12px;
    width: 100%;
    font-size: 14px;
    transition: border-color 0.15s;
}
.return-form input[type="text"]::placeholder,
.return-form input[type="number"]::placeholder,
.return-form textarea::placeholder {
    color: var(--text-dim);
}
.return-form input[type="text"]:focus,
.return-form input[type="number"]:focus,
.return-form textarea:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 2px rgba(255,184,0,0.1);
}
.charge-input {
    background: var(--black-3) !important;
    border: 1px solid var(--border) !important;
    color: var(--text-primary) !important;
    border-radius: var(--radius-sm) !important;
    padding: 8px 12px !important;
}
</style>
@endpush

@section('content')

<div class="return-form">
    <div class="form-section">
        <h3>Rental Information</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="color: var(--text-muted); font-size: 12px;">Rental ID</label>
                <p style="font-size: 18px; font-weight: 600;">{{ $rental->rental_id_display }}</p>
            </div>
            <div>
                <label style="color: var(--text-muted); font-size: 12px;">Customer</label>
                <p style="font-weight: 600;">{{ $rental->customer_name }}</p>
            </div>
            <div>
                <label style="color: var(--text-muted); font-size: 12px;">Vehicle</label>
                <p>{{ $rental->car->brand }} {{ $rental->car->model }} ({{ $rental->car->plate_number }})</p>
            </div>
            <div>
                <label style="color: var(--text-muted); font-size: 12px;">Rental Period</label>
                <p>{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('rentals.return', $rental) }}" enctype="multipart/form-data">
        @csrf

        {{-- Damage Assessment --}}
        <div class="form-section">
            <h3><i class="bi bi-tools"></i> Damage Assessment</h3>
            
            <div class="damage-counter">
                <button type="button" onclick="updateDamage(-1)">-</button>
                <input type="number" name="damage_panels" id="damage-panels" value="0" min="0" readonly>
                <button type="button" onclick="updateDamage(1)">+</button>
                <span style="margin-left: 12px; color: var(--text-muted);">damaged panels</span>
            </div>
            
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">
                Standard rate: &#8369;5,000 per damaged panel
            </p>
            
            <div class="form-group">
                <label>Damage Description</label>
                <textarea name="damage_description" rows="3" placeholder="Describe the damage details..." class="form-control"></textarea>
            </div>
            
            <div class="form-group">
                <label>Damage Photos</label>
                <input type="file" name="damage_photos[]" multiple accept="image/*" class="file-input">
                <small style="color: var(--text-muted);">Upload multiple photos of the damage</small>
            </div>
            
            <div class="damage-calculator">
                <div class="row">
                    <span>Damaged Panels</span>
                    <span id="calc-panels">0</span>
                </div>
                <div class="row">
                    <span>Rate per Panel</span>
                    <span>&#8369;5,000.00</span>
                </div>
                <div class="row total">
                    <span>Total Damage Fee</span>
                    <span id="calc-total">&#8369;0.00</span>
                </div>
            </div>
        </div>

        {{-- Vehicle Condition --}}
        <div class="form-section">
            <h3><i class="bi bi-fuel-pump"></i> Vehicle Condition</h3>
            
            <div class="form-group">
                <label>Fuel Level *</label>
                <div class="fuel-options">
                    <div class="fuel-option">
                        <input type="radio" name="fuel_level" id="fuel-full" value="full" required>
                        <label for="fuel-full">Full</label>
                    </div>
                    <div class="fuel-option">
                        <input type="radio" name="fuel_level" id="fuel-partial" value="partial" checked>
                        <label for="fuel-partial">Partial</label>
                    </div>
                    <div class="fuel-option">
                        <input type="radio" name="fuel_level" id="fuel-empty" value="empty">
                        <label for="fuel-empty">Empty</label>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Mileage (Returned)</label>
                    <input type="number" name="mileage_returned" placeholder="e.g., 50000" class="form-control">
                </div>
                <div class="form-group">
                    <label>Mileage (Start)</label>
                    <input type="number" name="mileage_start" placeholder="If recorded" class="form-control">
                </div>
            </div>
        </div>

        {{-- Additional Charges --}}
        <div class="form-section">
            <h3><i class="bi bi-cash-coin"></i> Additional Charges</h3>
            
            <div class="charge-row">
                <div>
                    <label>Fuel Charge</label>
                    <small style="color: var(--text-muted); display: block;">If fuel not full</small>
                </div>
                <input type="number" name="fuel_charge" value="0" step="0.01" class="charge-input" style="max-width: 150px; background: var(--black-3); border: 1px solid var(--border); color: var(--text-primary); border-radius: var(--radius-sm); padding: 8px 12px;">
            </div>
            
            <div class="charge-row">
                <div>
                    <label>Late Return Charge</label>
                    <small style="color: var(--text-muted); display: block;">If returned after due date</small>
                </div>
                <input type="number" name="late_return_charge" value="0" step="0.01" class="charge-input" style="max-width: 150px; background: var(--black-3); border: 1px solid var(--border); color: var(--text-primary); border-radius: var(--radius-sm); padding: 8px 12px;">
            </div>
            
            <div class="charge-row">
                <div>
                    <label>Cleaning Charge</label>
                    <small style="color: var(--text-muted); display: block;">If excessive cleaning needed</small>
                </div>
                <input type="number" name="cleaning_charge" value="0" step="0.01" class="charge-input" style="max-width: 150px; background: var(--black-3); border: 1px solid var(--border); color: var(--text-primary); border-radius: var(--radius-sm); padding: 8px 12px;">
            </div>
            
            <div class="charge-row">
                <div>
                    <label>Other Charges</label>
                    <input type="text" name="other_charges_notes" placeholder="Description..." style="margin-top: 4px; background: var(--black-3); border: 1px solid var(--border); color: var(--text-primary); border-radius: var(--radius-sm); padding: 8px 12px; width: 100%;">
                </div>
                <input type="number" name="other_charges" value="0" step="0.01" class="charge-input" style="max-width: 150px; background: var(--black-3); border: 1px solid var(--border); color: var(--text-primary); border-radius: var(--radius-sm); padding: 8px 12px;">
            </div>
        </div>

        {{-- Notes --}}
        <div class="form-section">
            <h3><i class="bi bi-journal-text"></i> Inspection Notes</h3>
            <div class="form-group">
                <textarea name="notes" rows="4" placeholder="Any additional observations about the vehicle condition..." class="form-control"></textarea>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Confirm vehicle return? This will finalize the rental.')">
                <i class="bi bi-check-circle"></i> Complete Return
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function updateDamage(change) {
    const input = document.getElementById('damage-panels');
    let value = parseInt(input.value) + change;
    if (value < 0) value = 0;
    input.value = value;
    calculateDamage();
}

function calculateDamage() {
    const panels = parseInt(document.getElementById('damage-panels').value) || 0;
    const rate = 5000;
    const total = panels * rate;
    
    document.getElementById('calc-panels').textContent = panels;
    document.getElementById('calc-total').textContent = '₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

// Initial calculation
calculateDamage();
</script>
@endpush

@endsection
