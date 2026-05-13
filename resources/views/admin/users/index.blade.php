@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">All Users</h5>
        <span class="badge bg-primary">{{ $users->total() }} Total Users</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Evaluations</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 35px; height: 35px;">
                                    {{ \App\Helpers\AnonymizeHelper::anonymizeInitial($user->id) }}
                                </div>
                                {{ \App\Helpers\AnonymizeHelper::anonymizeUser($user->id, 'User') }}
                            </div>
                        </td>
                        <td>{{ \App\Helpers\AnonymizeHelper::anonymizeEmail($user->id) }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->role->display_name ?? 'No Role' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $user->evaluations_count }}</span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-people display-6 d-block mb-2"></i>
                            No users found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
