@extends('layouts.admin')

@section('title', 'Messages')
@section('page-title', 'Contact Messages')

@section('content')
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Contact Messages</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr class="{{ $message->status === 'unread' ? 'table-warning' : '' }}">
                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ Str::limit($message->subject, 30) }}</td>
                        <td>
                            @if($message->status === 'unread')
                                <span class="badge bg-warning">Unread</span>
                            @elseif($message->status === 'read')
                                <span class="badge bg-info">Read</span>
                            @else
                                <span class="badge bg-success">Replied</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.messages.show', $message->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-envelope display-6 d-block mb-2"></i>
                            No messages found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection
