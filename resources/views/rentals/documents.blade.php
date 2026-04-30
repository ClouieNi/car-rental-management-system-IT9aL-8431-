@extends('layouts.app')

@section('title', 'Rental Documents')
@section('page-title', 'Manage Documents')
@section('breadcrumb', 'Upload and verify rental documents')

@push('styles')
<style>
.document-section {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 24px;
    margin-bottom: 24px;
}
.document-section h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.status-badge {
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 20px;
}
.upload-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-sm);
    padding: 32px;
    text-align: center;
    background: var(--black-3);
    transition: all 0.15s;
}
.upload-area:hover {
    border-color: var(--gold);
    background: var(--gold-muted);
}
.upload-area i {
    font-size: 48px;
    color: var(--text-muted);
    margin-bottom: 12px;
}
.file-input {
    display: none;
}
.file-label {
    cursor: pointer;
    color: var(--gold);
    text-decoration: underline;
}
.preview-box {
    background: var(--black-3);
    border-radius: var(--radius-sm);
    padding: 16px;
    margin-top: 16px;
}
.verification-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}
.document-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
@media (max-width: 768px) {
    .document-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')

<div class="page-header">
    <h2>Documents: {{ $rental->rental_id_display }}</h2>
    <div>
        <span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span>
    </div>
</div>

<div class="document-grid">
    {{-- Contract Section --}}
    <div class="document-section">
        <h3>
            <i class="bi bi-file-text"></i>
            Rental Contract
            <span class="badge badge-{{ $rental->document_status_color }} status-badge">{{ ucfirst($rental->contract_status) }}</span>
        </h3>
        
        @if($rental->contract_file_path)
            <div class="preview-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="bi bi-file-pdf" style="font-size: 24px; color: var(--gold);"></i>
                        <span style="margin-left: 12px;">Contract uploaded</span>
                    </div>
                    <a href="{{ route('rentals.download-contract', $rental) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
                @if($rental->contract_verified_at)
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-subtle);">
                        <small style="color: var(--text-muted);">
                            <i class="bi bi-check-circle-fill" style="color: #4ADE80;"></i>
                            Verified by {{ $rental->contractVerifiedBy?->name ?? 'Staff' }} 
                            on {{ $rental->contract_verified_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                @endif
            </div>
        @else
            <div class="upload-area" onclick="document.getElementById('contract-input').click()">
                <i class="bi bi-cloud-upload"></i>
                <p>Click to upload contract (PDF or Image)</p>
                <small style="color: var(--text-muted);">Max 5MB</small>
            </div>
        @endif
        
        <form method="POST" action="{{ route('rentals.upload-contract', $rental) }}" enctype="multipart/form-data" id="contract-form">
            @csrf @method('PATCH')
            <input type="file" name="contract_file" id="contract-input" class="file-input" 
                   accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('contract-form').submit()">
        </form>
        
        @if($rental->contract_file_path && $rental->contract_status !== 'verified')
            <div class="verification-actions">
                <form method="POST" action="{{ route('rentals.verify-documents', $rental) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="document_type" value="contract">
                    <input type="hidden" name="action" value="verify">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-lg"></i> Verify Contract
                    </button>
                </form>
                <form method="POST" action="{{ route('rentals.verify-documents', $rental) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="document_type" value="contract">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-lg"></i> Reject
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- ID Section --}}
    <div class="document-section">
        <h3>
            <i class="bi bi-person-badge"></i>
            Driver's License / ID
            <span class="badge badge-{{ $rental->id_status_color }} status-badge">{{ ucfirst($rental->id_status) }}</span>
        </h3>
        
        @if($rental->id_file_path)
            <div class="preview-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="bi bi-file-image" style="font-size: 24px; color: var(--gold);"></i>
                        <span style="margin-left: 12px;">ID uploaded</span>
                    </div>
                    <a href="{{ route('rentals.download-id', $rental) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
                @if($rental->id_verified_at)
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-subtle);">
                        <small style="color: var(--text-muted);">
                            <i class="bi bi-check-circle-fill" style="color: #4ADE80;"></i>
                            Verified by {{ $rental->idVerifiedBy?->name ?? 'Staff' }} 
                            on {{ $rental->id_verified_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                @endif
            </div>
        @else
            <div class="upload-area" onclick="document.getElementById('id-input').click()">
                <i class="bi bi-cloud-upload"></i>
                <p>Click to upload ID (Image)</p>
                <small style="color: var(--text-muted);">Max 5MB</small>
            </div>
        @endif
        
        <form method="POST" action="{{ route('rentals.upload-id', $rental) }}" enctype="multipart/form-data" id="id-form">
            @csrf @method('PATCH')
            <input type="file" name="id_file" id="id-input" class="file-input" 
                   accept=".jpg,.jpeg,.png" onchange="document.getElementById('id-form').submit()">
        </form>
        
        @if($rental->id_file_path && $rental->id_status !== 'verified')
            <div class="verification-actions">
                <form method="POST" action="{{ route('rentals.verify-documents', $rental) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="document_type" value="id">
                    <input type="hidden" name="action" value="verify">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-lg"></i> Verify ID
                    </button>
                </form>
                <form method="POST" action="{{ route('rentals.verify-documents', $rental) }}" style="display: inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="document_type" value="id">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-lg"></i> Reject
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="document-section">
    <h3>Document Requirements</h3>
    <ul style="color: var(--text-secondary); margin-left: 20px;">
        <li>Contract must be signed by customer</li>
        <li>Driver's license must be valid and not expired before rental end date</li>
        <li>Both documents must be verified before vehicle can be released</li>
        <li>Maximum file size: 5MB per document</li>
    </ul>
</div>

<div style="display: flex; gap: 12px; margin-top: 24px;">
    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Rental
    </a>
    @if($rental->isDocumentsComplete() && in_array($rental->status, ['approved', 'documents_pending', 'documents_verified']))
        <form method="POST" action="{{ route('rentals.request-documents', $rental) }}" style="display: inline;">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="complete">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Documents Complete - Proceed
            </button>
        </form>
    @endif
</div>

@endsection
