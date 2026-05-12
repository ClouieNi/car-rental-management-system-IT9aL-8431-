@extends('layouts.app')

@section('title', 'Rental #' . $rental->id)
@section('page-title', 'My Rental')
@section('breadcrumb', 'Rental Details')

@section('content')

<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; color: var(--gold); margin-bottom: 8px;">
                Rental #{{ $rental->id }}
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <span class="badge badge-{{ $rental->status_color }}">{{ ucfirst(str_replace('_', ' ', $rental->status)) }}</span>
                <span style="color: var(--text-muted); font-size: 13px;">Created {{ $rental->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            @if(in_array($rental->status, ['approved', 'documents_pending']))
                <a href="{{ route('customer.rental.documents', $rental) }}" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Upload Documents
                </a>
            @endif
            @if(in_array($rental->status, ['pending', 'approved']))
                <button onclick="document.getElementById('cancel-modal').style.display='block'" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Request Cancellation
                </button>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column -->
    <div class="lg:col-span-2">
        <!-- Vehicle Info -->
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                Vehicle Information
            </h3>
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="width: 80px; height: 80px; background: var(--black-1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-car-front" style="font-size: 40px; color: var(--gold);"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">
                        {{ $rental->car->brand }} {{ $rental->car->model }} {{ $rental->car->year }}
                    </div>
                    <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px;">
                        Plate: {{ $rental->car->plate_number }}
                    </div>
                    @if($rental->car->supplier && $rental->car->supplier->isCompanyOwned())
                        <div style="font-size: 12px;">
                            <span style="color: #4ADE80;"><i class="bi bi-building"></i> Company Vehicle</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Rental Dates & Pricing -->
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                Rental Details
            </h3>
            <div class="grid grid-cols-2 gap-4" style="margin-bottom: 16px;">
                <div style="padding: 12px; background: var(--black-1); border-radius: 8px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Start Date</label>
                    <div style="font-weight: 500;">{{ $rental->start_date->format('M d, Y') }}</div>
                </div>
                <div style="padding: 12px; background: var(--black-1); border-radius: 8px;">
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">End Date</label>
                    <div style="font-weight: 500;">{{ $rental->end_date->format('M d, Y') }}</div>
                </div>
            </div>
            
            <div style="border-top: 1px solid var(--border-subtle); padding-top: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: var(--text-muted);">Base Cost</span>
                    <span>₱{{ number_format($rental->total_cost - ($rental->distance_surcharge ?? 0), 2) }}</span>
                </div>
                @if($rental->distance_surcharge > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--text-muted);">Distance Surcharge</span>
                        <span>₱{{ number_format($rental->distance_surcharge, 2) }}</span>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between; padding-top: 8px; border-top: 1px solid var(--border-subtle); font-weight: 600;">
                    <span>Total Cost</span>
                    <span style="color: var(--gold);">₱{{ number_format($rental->total_cost, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Documents Section -->
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                Documents
            </h3>
            
            @if(session('doc_success'))
                <div class="alert alert-success" style="margin-bottom: 16px; padding: 12px 16px; background: #10B98120; border: 1px solid #10B981; border-radius: 8px; color: #10B981;">
                    <i class="bi bi-check-circle"></i> {{ session('doc_success') }}
                </div>
            @endif
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <!-- Contract -->
                <form method="POST" action="{{ route('customer.rental.upload-documents', $rental) }}" enctype="multipart/form-data" style="margin: 0;">
                    @csrf
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--black-1); border-radius: 8px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $rental->contract_file_path ? ($rental->contract_status === 'verified' ? '#10B981' : '#F59E0B') : '#374151' }};">
                            <i class="bi bi-file-text" style="color: white;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500;">Rental Contract</div>
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
                                    Not uploaded yet
                                @endif
                            </small>
                        </div>
                        @if(in_array($rental->status, ['reserved', 'approved', 'documents_pending']))
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="file" name="contract_file" id="contract_file" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="this.form.submit()">
                                <label for="contract_file" class="btn btn-secondary btn-sm" style="cursor: pointer; margin: 0;">
                                    <i class="bi bi-upload"></i> {{ $rental->contract_file_path ? 'Replace' : 'Upload' }}
                                </label>
                            </div>
                        @endif
                    </div>
                </form>
                
                <!-- ID -->
                <form method="POST" action="{{ route('customer.rental.upload-documents', $rental) }}" enctype="multipart/form-data" style="margin: 0;">
                    @csrf
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--black-1); border-radius: 8px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $rental->id_file_path ? '#10B981' : '#374151' }};">
                            <i class="bi bi-person-badge" style="color: white;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500;">Valid ID</div>
                            <small style="color: {{ $rental->id_file_path ? '#10B981' : '#9CA3AF' }};">
                                @if($rental->id_file_path)
                                    Uploaded ✓
                                @else
                                    Not uploaded yet
                                @endif
                            </small>
                        </div>
                        @if(in_array($rental->status, ['reserved', 'approved', 'documents_pending']))
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="file" name="id_file" id="id_file" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="this.form.submit()">
                                <label for="id_file" class="btn btn-secondary btn-sm" style="cursor: pointer; margin: 0;">
                                    <i class="bi bi-upload"></i> {{ $rental->id_file_path ? 'Replace' : 'Upload' }}
                                </label>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Messages -->
        @if($rental->messages && $rental->messages->count() > 0)
            <div class="card">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                    Messages
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($rental->messages as $message)
                        <div style="padding: 12px; background: var(--black-1); border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span style="font-weight: 500;">{{ $message->subject ?? 'Message' }}</span>
                                <small style="color: var(--text-muted);">{{ $message->created_at->format('M d, H:i') }}</small>
                            </div>
                            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">{{ $message->message }}</p>
                            @if($message->admin_reply)
                                <div style="margin-top: 8px; padding: 8px; background: var(--gold-muted); border-radius: 6px;">
                                    <small style="color: var(--gold);"><strong>Reply:</strong> {{ $message->admin_reply }}</small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <!-- Right Column -->
    <div class="lg:col-span-1">
        <!-- Driver Info -->
        @if($rental->driver)
            <div class="card" style="margin-bottom: 24px;">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                    Driver Information
                </h3>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 12px; color: var(--text-muted);">License Number</label>
                    <div style="font-weight: 500;">{{ $rental->driver->license_number }}</div>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted);">License Expiry</label>
                    <div style="font-weight: 500;">{{ $rental->driver->license_expiry->format('M d, Y') }}</div>
                </div>
            </div>
        @endif
        
        <!-- Payment Section (for reserved rentals not fully paid) -->
        @if($rental->status === 'reserved' && $rental->payment_status !== 'paid')
            <div class="card" style="margin-bottom: 24px; border-color: var(--gold);">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--gold); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                    <i class="bi bi-credit-card"></i> Payment Required
                </h3>
                
                <div style="margin-bottom: 16px; padding: 12px; background: var(--black-1); border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--text-muted); font-size: 13px;">Total Cost</span>
                        <span style="font-weight: 500;">₱{{ number_format($rental->total_cost, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--text-muted); font-size: 13px;">Amount Paid</span>
                        <span style="font-weight: 500; color: #10B981;">₱{{ number_format($rental->amount_paid, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 8px; border-top: 1px solid var(--border-subtle);">
                        <span style="color: var(--text-muted); font-size: 13px;">Balance Due</span>
                        <span style="font-weight: 600; color: var(--gold);">₱{{ number_format($rental->total_cost - $rental->amount_paid, 2) }}</span>
                    </div>
                </div>
                
                @if(session('payment_success'))
                    <div style="margin-bottom: 16px; padding: 12px; background: #10B98120; border-radius: 8px; color: #10B981; font-size: 13px;">
                        <i class="bi bi-check-circle"></i> {{ session('payment_success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('customer.rental.payment', $rental) }}">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Payment Amount (₱)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" max="{{ $rental->total_cost - $rental->amount_paid }}" 
                               value="{{ $rental->total_cost - $rental->amount_paid }}" required
                               style="width: 100%; padding: 10px 12px; background: var(--black-1); border: 1px solid var(--border-subtle); border-radius: 6px; color: var(--text-primary); font-size: 14px; -webkit-appearance: none; -moz-appearance: textfield;">
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Payment Method</label>
                        <div style="position: relative;">
                            <select name="payment_method" required
                                    style="width: 100%; padding: 10px 32px 10px 12px; background: var(--black-1); border: 1px solid var(--border-subtle); border-radius: 6px; color: var(--text-primary); font-size: 14px; appearance: none; -webkit-appearance: none; cursor: pointer;">
                                <option value="" style="background: #1a1a1a; color: #9ca3af;">Select method...</option>
                                <option value="gcash" style="background: #1a1a1a; color: #ededec;">GCash</option>
                                <option value="maya" style="background: #1a1a1a; color: #ededec;">Maya</option>
                                <option value="bank_transfer" style="background: #1a1a1a; color: #ededec;">Bank Transfer</option>
                                <option value="cash" style="background: #1a1a1a; color: #ededec;">Cash</option>
                            </select>
                            <i class="bi bi-chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 12px;"></i>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Reference Number</label>
                        <input type="text" name="reference_number" placeholder="e.g., GCash ref # or Bank TXN ID" required
                               style="width: 100%; padding: 10px 12px; background: var(--black-1); border: 1px solid var(--border-subtle); border-radius: 6px; color: var(--text-primary); font-size: 14px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">
                        <i class="bi bi-credit-card"></i> Record Payment
                    </button>
                </form>
                
                <p style="margin-top: 12px; font-size: 11px; color: var(--text-muted); text-align: center;">
                    <i class="bi bi-info-circle"></i> Payment will be verified by staff before pickup
                </p>
            </div>
        @endif

        <!-- Status Info -->
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                What Happens Next?
            </h3>
            
            @switch($rental->status)
                @case('pending')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Your booking is awaiting staff approval. You will be notified once it's approved.
                    </p>
                    @break
                    
                @case('approved')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 12px;">
                        Your booking has been approved! Please upload your signed contract and valid ID to proceed.
                    </p>
                    <a href="{{ route('customer.rental.documents', $rental) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                        <i class="bi bi-upload"></i> Upload Documents
                    </a>
                    @break
                    
                @case('documents_pending')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 12px;">
                        Your documents have been uploaded and are awaiting staff verification.
                    </p>
                    <div style="padding: 12px; background: #F59E0B20; border-radius: 8px; text-align: center;">
                        <i class="bi bi-clock" style="color: #F59E0B; font-size: 20px;"></i>
                        <small style="color: #F59E0B; display: block; margin-top: 4px;">Verification in progress</small>
                    </div>
                    @break
                    
                @case('documents_verified')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Your documents have been verified! Your vehicle is reserved and ready for pickup on the start date.
                    </p>
                    @break
                    
                @case('reserved')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Your vehicle is reserved. Please arrive on time for pickup with your valid driver's license.
                    </p>
                    @break
                    
                @case('ongoing')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Your rental is active. Please return the vehicle by {{ $rental->end_date->format('M d, Y') }}.
                    </p>
                    @break
                    
                @case('completed')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Your rental has been completed. Thank you for choosing Cars ni Bai!
                    </p>
                    @break
                    
                @case('cancelled')
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        This rental has been cancelled.
                        @if($rental->refund_amount > 0)
                            <br><br>Refund amount: ₱{{ number_format($rental->refund_amount, 2) }}
                        @endif
                    </p>
                    @break
                    
                @default
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                        Status: {{ ucfirst(str_replace('_', ' ', $rental->status)) }}
                    </p>
            @endswitch
        </div>
        
        <!-- Need Help -->
        <div class="card" style="background: var(--gold-muted); border-color: var(--gold);">
            <h3 style="font-size: 14px; font-weight: 600; color: var(--gold); margin-bottom: 12px;">
                <i class="bi bi-question-circle"></i> Need Help?
            </h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                Have questions about your rental? Send us a message.
            </p>
            <a href="{{ route('customer.messages') }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                <i class="bi bi-chat-square-text"></i> Contact Support
            </a>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
@if(in_array($rental->status, ['pending', 'approved']))
<div id="cancel-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--black-2); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 24px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 16px;">Request Cancellation</h3>
        <p style="color: var(--text-muted); margin-bottom: 16px;">
            Are you sure you want to cancel this rental? 
            @if($rental->calculateRefundPercent() < 100)
                A {{ 100 - $rental->calculateRefundPercent() }}% cancellation fee will apply.
            @endif
        </p>
        
        <form method="POST" action="{{ route('customer.rental.cancel-request', $rental) }}">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 4px; font-size: 13px;">Reason for cancellation</label>
                <textarea name="cancellation_reason" required style="width: 100%; padding: 8px; background: var(--black-1); border: 1px solid var(--border-subtle); border-radius: 6px; color: var(--text-primary); resize: vertical; min-height: 80px;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('cancel-modal').style.display='none'" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
