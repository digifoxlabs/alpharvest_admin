@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Admin Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Role and permission overview for the `web` guard.</p>
        </div>

        @can('create users')
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Create User
        </a>
        @endcan
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Users</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['users'] }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Roles</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['roles'] }}</h3>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Permissions</p>
            <h3 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['permissions'] }}</h3>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        {{-- <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Blade Authorization Example</h3>
            <div class="mt-4 rounded-xl bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <pre class="whitespace-pre-wrap">
                    @can('create users')
                    show button
                @endcan
            </pre>
            </div>

            @can('create users')
            <p class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                You can create users because `@can('create users')` passed.
            </p>
            @endcan
        </div> --}}

        {{-- <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Role Example</h3>
            <div class="mt-4 rounded-xl bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <pre class="whitespace-pre-wrap">
                    @role('admin')
                        show admin-only section
                        @endrole
                </pre>
            </div>

            @role('admin')
            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                This admin-only section is visible because your role includes `admin`.
            </div>
            @endrole

        </div> --}}
    </div>

</div>
@endsection

@push('scripts')
<script>
    window.pageXData = {
        page: 'dashboard',
    };
</script>
@endpush
