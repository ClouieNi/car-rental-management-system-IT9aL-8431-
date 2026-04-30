@extends('layouts.app')

@section('title', 'Messages')
@section('page-title', 'Customer Messages')
@section('breadcrumb', 'Manage customer inquiries and replies')

@push('styles')
<style>
.message-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.message-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    transition: all 0.15s;
}
.message-item:hover {
    border-color: var(--border);
    background: var(--black-3);
}
.message-item.unread {
    border-left: 3px solid var(--gold);
    background: var(--gold-muted);
}
.message-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--gold-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    font-size: 18px;
    flex-shrink: 0;
}
.message-content {
    flex: 1;
    min-width: 0;
}
.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.message-sender {
    font-weight: 600;
    font-size: 14px;
    color: var(--text-primary);
}
.message-date {
    font-size: 12px;
    color: var(--text-muted);
}
.message-subject {
    font-weight: 500;
    font-size: 13px;
    color: var(--text-primary);
    margin-bottom: 4px;
}
.message-preview {
    font-size: 12px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.message-status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
}
.status-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.unread {
    background: var(--gold);
    color: var(--black);
}
.status-badge.read {
    background: rgba(255,255,255,0.08);
    color: var(--text-muted);
}
.status-badge.replied {
    background: rgba(34,197,94,0.15);
    color: #4ADE80;
}
.rental-info {
    font-size: 11px;
    color: var(--text-muted);
}
.pagination-container {
    margin-top: 20px;
}
</style>
@endpush

@section('content')

<div class="card">
    <div class="message-list">
        @forelse($messages as $message)
        <a href="{{ route('messages.show', $message) }}" class="message-item {{ $message->is_read ? '' : 'unread' }}">
            <div class="message-avatar">
                <i class="bi bi-person"></i>
            </div>
            <div class="message-content">
                <div class="message-header">
                    <span class="message-sender">{{ $message->user->name }}</span>
                    <span class="message-date">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                <div class="message-subject">{{ $message->subject }}</div>
                <div class="message-preview">{{ Str::limit($message->message, 100) }}</div>
            </div>
            <div class="message-status">
                @if($message->admin_reply)
                    <span class="status-badge replied">Replied</span>
                @elseif($message->is_read)
                    <span class="status-badge read">Read</span>
                @else
                    <span class="status-badge unread">New</span>
                @endif
                @if($message->rental)
                    <span class="rental-info">{{ $message->rental->car->brand }} {{ $message->rental->car->model }}</span>
                @endif
            </div>
        </a>
        @empty
        <div class="empty-state">
            <i class="bi bi-inbox" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
            <p style="color: var(--text-muted);">No messages found.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="pagination-container">
    {{ $messages->links() }}
</div>

@endsection
