@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Permission</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update the permission name for the `web` guard.</p>
    </div>

    @include('admin.permissions.partials.form', [
        'action' => route('admin.permissions.update', $permission),
        'method' => 'PUT',
        'permission' => $permission,
    ])
</div>
@endsection
