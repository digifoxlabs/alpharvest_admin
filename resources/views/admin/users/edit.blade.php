@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit User</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update account details and role assignment.</p>
    </div>

    @include('admin.users.partials.form', [
        'action' => route('admin.users.update', $user),
        'method' => 'PUT',
        'user' => $user,
        'roles' => $roles,
    ])
</div>
@endsection
