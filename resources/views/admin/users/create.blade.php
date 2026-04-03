@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create User</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Create a new admin panel user and assign a role.</p>
    </div>

    @include('admin.users.partials.form', [
        'action' => route('admin.users.store'),
        'method' => 'POST',
        'user' => null,
        'roles' => $roles,
    ])
</div>
@endsection
