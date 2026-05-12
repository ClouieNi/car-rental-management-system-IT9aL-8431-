@extends('layouts.app')

@section('title', 'Upload Documents')
@section('page-title', 'Document Upload')
@section('breadcrumb', 'Rental #' . $rental->id)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Rental Info -->
    <div class="lg:col-span-1">
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gold);">Rental Information</h3>
            
            <div style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--text-muted);">Vehicle</label>
                <div style="font-weight: 500;">{{ $rental->car->brand }} {{ $rental->car->model }}</div>
                <small style="color: #9ca3af;">{{ $rental->car->plate_number }}</small>
            </div>
            
            <div style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--text-muted);">Rental Dates</label>
                <div style="font-weight: 500;">{{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}</div>
            </div>
            
            <div style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--text-muted);">Total Cost</label>
                <div style="font-weight: 500;">₱{{ number_format($rental->total_cost, 2) }}</div>
            </div>
            
            <div>
                <label style="font-size: 12px; color: var(--text-muted);">Status</label>
                <div>
                    <span class="badge badge-{{ $rental->status_color }}">{{ ucfirst(str_replace('_', ' ', $rental->status)) }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: var(--text-muted);">Document Status</h3>
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; padding: 12px; background: var(--black-1); border-radius: 8px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $rental->contract_file_path ? ($rental->contract_status === 'verified' ? '#10B981' : '#F59E0B') : '#374151' }};">
                    <i class="bi bi-file-text" style="color: white;"></i>
                </div>
                <div>
                    <div style="font-weight: 500;">Contract</div>
                    <small style="color: {{ $rental->contract_file_path ? ($rental->contract_status === 'verified' ? '#10B981' : '#F59E0B') : '#9CA3AF' }};">
                        @if($rental->contract_file_path)
                            @if($rental->contract_status === 'verified')
                                Verified ✓
                            @elseif($rental->contract_status === 'rejected')
                                Rejected - Please reupload
                            @else
                                Uploaded - Awaiting verification
                            @endif
                        @else
                            Not uploaded
                        @endif
                    </small>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--black-1); border-radius: 8px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $rental->id_file_path ? '#10B981' : '#374151' }};">
                    <i class="bi bi-person-badge" style="color: white;"></i>
                </div>
                <div>
                    <div style="font-weight: 500;">Valid ID</div>
                    <small style="color: {{ $rental->id_file_path ? '#10B981' : '#9CA3AF' }};">
                        @if($rental->id_file_path)
                            Uploaded ✓
                        @else
                            Not uploaded
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Upload Forms -->
    <div class="lg:col-span-2">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Upload Required Documents</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">
                Please upload your signed contract and a valid ID to proceed with your rental. 
                Documents will be reviewed by our staff before vehicle release.
            </p>
            
            <!-- Sample Documents -->
            <div style="margin-bottom: 24px; padding: 16px; background: var(--black-1); border-radius: 8px; border: 1px solid var(--border-subtle);">
                <h4 style="font-size: 13px; font-weight: 600; margin-bottom: 12px; color: var(--gold);">
                    <i class="bi bi-info-circle"></i> Sample Documents (Download for Reference)
                </h4>
                <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <a href="/sample_documents/sample_contract.txt" target="_blank" 
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; background: #3B82F620; border: 1px solid #3B82F6; border-radius: 6px; color: #3B82F6; font-size: 13px; text-decoration: none;">
                        <i class="bi bi-file-earmark-text"></i> Sample Contract (.txt)
                    </a>
                    @if(file_exists(public_path('sample_documents/sample_id.png')))
                        <a href="/sample_documents/sample_id.png" target="_blank" 
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; background: #10B98120; border: 1px solid #10B981; border-radius: 6px; color: #10B981; font-size: 13px; text-decoration: none;">
                            <i class="bi bi-person-badge"></i> Sample ID (.png)
                        </a>
                    @else
                        <span style="font-size: 12px; color: var(--text-muted);">
                            <i class="bi bi-info-circle"></i> ID: Upload Driver's License, Passport, or National ID
                        </span>
                    @endif
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('customer.rental.upload-documents', $rental) }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Contract Upload -->
                <div style="margin-bottom: 24px; padding: 20px; border: 2px dashed var(--border-subtle); border-radius: 12px; background: var(--black-1);">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--gold-muted); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-file-earmark-text" style="font-size: 24px; color: var(--gold);"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <h4 style="font-size: 14px; font-weight: 600;">Rental Contract</h4>
                                @if(!$rental->contract_file_path)
                                    <span style="padding: 2px 8px; background: #EF4444; color: white; font-size: 11px; border-radius: 4px; font-weight: 600;">REQUIRED</span>
                                @else
                                    <span style="padding: 2px 8px; background: #10B981; color: white; font-size: 11px; border-radius: 4px; font-weight: 600;">UPLOADED</span>
                                @endif
                            </div>
                            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                                Upload the signed rental agreement. Accepted formats: PDF, JPG, PNG (max 10MB)
                            </p>
                            
                            @if($rental->contract_file_path)
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px 12px; background: #10B98120; border-radius: 6px;">
                                    <i class="bi bi-check-circle" style="color: #10B981;"></i>
                                    <span style="font-size: 13px; color: #10B981;">Contract already uploaded</span>
                                </div>
                            @endif
                            
                            <input type="file" name="contract_file" id="contract_file" accept=".pdf,.jpg,.jpeg,.png" 
                                style="display: none;" onchange="updateFileName('contract_file', 'contract-label')">
                            <label for="contract_file" class="btn btn-secondary btn-sm" style="cursor: pointer;">
                                <i class="bi bi-upload"></i> 
                                <span id="contract-label">{{ $rental->contract_file_path ? 'Replace Contract' : 'Choose File' }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- ID Upload -->
                <div style="margin-bottom: 24px; padding: 20px; border: 2px dashed var(--border-subtle); border-radius: 12px; background: var(--black-1);">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--gold-muted); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-badge" style="font-size: 24px; color: var(--gold);"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <h4 style="font-size: 14px; font-weight: 600;">Valid ID</h4>
                                @if(!$rental->id_file_path)
                                    <span style="padding: 2px 8px; background: #EF4444; color: white; font-size: 11px; border-radius: 4px; font-weight: 600;">REQUIRED</span>
                                @else
                                    <span style="padding: 2px 8px; background: #10B981; color: white; font-size: 11px; border-radius: 4px; font-weight: 600;">UPLOADED</span>
                                @endif
                            </div>
                            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                                Upload a valid government ID (Driver's License, Passport, National ID). Accepted formats: PDF, JPG, PNG (max 5MB)
                            </p>
                            
                            @if($rental->id_file_path)
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px 12px; background: #10B98120; border-radius: 6px;">
                                    <i class="bi bi-check-circle" style="color: #10B981;"></i>
                                    <span style="font-size: 13px; color: #10B981;">ID already uploaded</span>
                                </div>
                            @endif
                            
                            <input type="file" name="id_file" id="id_file" accept=".pdf,.jpg,.jpeg,.png" 
                                style="display: none;" onchange="updateFileName('id_file', 'id-label')">
                            <label for="id_file" class="btn btn-secondary btn-sm" style="cursor: pointer;">
                                <i class="bi bi-upload"></i> 
                                <span id="id-label">{{ $rental->id_file_path ? 'Replace ID' : 'Choose File' }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('customer.rental-show', $rental) }}" class="btn btn-secondary">Back to Rental</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload"></i> Upload Documents
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Important Notes -->
        <div class="card" style="background: #EF444420; border-color: #EF4444;">
            <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: #EF4444;">
                <i class="bi bi-exclamation-triangle-fill"></i> Strictly Required
            </h4>
            <ul style="font-size: 13px; color: var(--text-secondary); margin: 0; padding-left: 20px;">
                <li style="margin-bottom: 8px;"><strong>BOTH documents are required</strong> - You must upload the signed contract AND a valid ID</li>
                <li style="margin-bottom: 8px;">Form will not submit if either document is missing</li>
                <li style="margin-bottom: 8px;">Documents are securely stored and only accessible to authorized staff</li>
                <li style="margin-bottom: 8px;">Verification typically takes 1-2 business hours</li>
                <li>Once verified, you'll be able to proceed with vehicle pickup</li>
            </ul>
        </div>
    </div>
</div>

<script>
function updateFileName(inputId, labelId) {
    const input = document.getElementById(inputId);
    const label = document.getElementById(labelId);
    if (input.files && input.files.length > 0) {
        label.textContent = input.files[0].name;
    }
}
</script>

@endsection
