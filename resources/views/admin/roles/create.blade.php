@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create Role</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Define a role and select the permissions it should receive.</p>
    </div>

    @include('admin.roles.partials.form', [
        'action' => route('admin.roles.store'),
        'method' => 'POST',
        'role' => null,
        'permissions' => $permissions,
    ])
</div>
@endsection
