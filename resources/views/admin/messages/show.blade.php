@extends('layouts.admin')

@section('title', 'Message Details')
@section('page-title', 'Message Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-envelope-open me-2"></i>{{ $message->subject }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between text-muted small mb-3">
                    <span><i class="bi bi-person me-1"></i>{{ $message->name }} ({{ $message->email }})</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $message->created_at->format('M d, Y H:i A') }}</span>
                </div>
                <hr>
                <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
            </div>
        </div>

        @if($message->admin_reply)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-reply me-2"></i>Admin Reply</h5>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-3">
                        <i class="bi bi-clock me-1"></i>Replied on {{ $message->replied_at->format('M d, Y H:i A') }}
                    </div>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $message->admin_reply }}</p>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-reply me-2"></i>Send Reply</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.messages.reply', $message->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="reply" class="form-control" rows="5" 
                                      placeholder="Type your reply here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-spup">
                            <i class="bi bi-send me-2"></i>Send Reply
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Message Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($message->status === 'unread')
                                <span class="badge bg-warning">Unread</span>
                            @elseif($message->status === 'read')
                                <span class="badge bg-info">Read</span>
                            @else
                                <span class="badge bg-success">Replied</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>From:</th>
                        <td>{{ $message->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Received:</th>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Back to Messages
            </a>
        </div>
    </div>
</div>
@endsection
