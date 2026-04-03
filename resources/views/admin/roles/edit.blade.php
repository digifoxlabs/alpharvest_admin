@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Role</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update role name and permission mapping.</p>
    </div>

    @include('admin.roles.partials.form', [
        'action' => route('admin.roles.update', $role),
        'method' => 'PUT',
        'role' => $role,
        'permissions' => $permissions,
    ])
</div>
@endsection
