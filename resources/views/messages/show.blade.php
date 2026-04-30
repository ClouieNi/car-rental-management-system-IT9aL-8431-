@extends('layouts.app')

@section('title', 'Message: ' . $message->subject)
@section('page-title', 'Message Details')
@section('breadcrumb', 'View and reply to customer message')

@push('styles')
<style>
.message-container {
    max-width: 800px;
    margin: 0 auto;
}
.message-thread {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    overflow: hidden;
}
.message-bubble {
    padding: 20px;
    border-bottom: 1px solid var(--border-subtle);
}
.message-bubble:last-child {
    border-bottom: none;
}
.message-bubble.customer {
    background: var(--black-2);
}
.message-bubble.admin {
    background: var(--gold-muted);
}
.message-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.message-author {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 14px;
}
.message-author i {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.message-bubble.customer .message-author i {
    background: var(--black-3);
    color: var(--text-muted);
}
.message-bubble.admin .message-author i {
    background: var(--gold);
    color: var(--black);
}
.message-time {
    font-size: 12px;
    color: var(--text-muted);
}
.message-body {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-primary);
    padding-left: 42px;
}
.message-subject-header {
    padding: 16px 20px;
    background: var(--black-3);
    border-bottom: 1px solid var(--border-subtle);
}
.message-subject-header h2 {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}
.rental-context {
    margin-top: 8px;
    font-size: 12px;
    color: var(--text-muted);
}
.rental-context a {
    color: var(--gold);
    text-decoration: none;
}
.rental-context a:hover {
    text-decoration: underline;
}
.reply-section {
    margin-top: 24px;
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 20px;
}
.reply-section h3 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--text-primary);
}
.reply-form textarea {
    width: 100%;
    padding: 12px;
    background: var(--black-3);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 14px;
    min-height: 120px;
    resize: vertical;
    margin-bottom: 16px;
}
.reply-form textarea:focus {
    outline: none;
    border-color: var(--gold);
}
.message-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
}
.status-indicator i {
    font-size: 10px;
}
.status-indicator.replied {
    color: #4ADE80;
}
</style>
@endpush

@section('content')

<div class="message-container">
    <div class="message-thread">
        <div class="message-subject-header">
            <h2>{{ $message->subject }}</h2>
            @if($message->rental)
            <div class="rental-context">
                Related to rental: 
                <a href="{{ route('rentals.show', $message->rental) }}">
                    {{ $message->rental->car->brand }} {{ $message->rental->car->model }} 
                    ({{ $message->rental->start_date->format('M d') }} - {{ $message->rental->end_date->format('M d, Y') }})
                </a>
            </div>
            @endif
        </div>
        
        <div class="message-bubble customer">
            <div class="message-meta">
                <div class="message-author">
                    <i class="bi bi-person"></i>
                    {{ $message->user->name }}
                </div>
                <div class="message-time">
                    {{ $message->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="message-body">{{ $message->message }}</div>
        </div>

        @if($message->admin_reply)
        <div class="message-bubble admin">
            <div class="message-meta">
                <div class="message-author">
                    <i class="bi bi-shield-check"></i>
                    Admin
                </div>
                <div class="message-time">
                    {{ $message->replied_at?->format('M d, Y h:i A') ?? 'Replied' }}
                </div>
            </div>
            <div class="message-body">{{ $message->admin_reply }}</div>
        </div>
        @endif
    </div>

    @if(!$message->admin_reply)
    <div class="reply-section">
        <h3>Send Reply</h3>
        <form method="POST" action="{{ route('messages.reply', $message) }}" class="reply-form">
            @csrf
            <textarea name="reply" placeholder="Type your reply here..." required></textarea>
            <button type="submit" class="btn btn-primary">Send Reply</button>
        </form>
    </div>
    @else
    <div style="text-align: center; margin-top: 20px; color: var(--text-muted); font-size: 13px;">
        <i class="bi bi-check-circle"></i> You replied to this message on {{ $message->replied_at->format('M d, Y h:i A') }}
    </div>
    @endif

    <div class="message-actions">
        <a href="{{ route('messages.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Messages
        </a>
    </div>
</div>

@endsection
