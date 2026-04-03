@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create Permission</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">New permissions are added to the admin role automatically.</p>
    </div>

    @include('admin.permissions.partials.form', [
        'action' => route('admin.permissions.store'),
        'method' => 'POST',
        'permission' => null,
    ])
</div>
@endsection
