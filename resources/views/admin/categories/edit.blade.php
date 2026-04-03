@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Category</h2><p class="text-sm text-gray-500 dark:text-gray-400">Update product category details.</p></div>
    @include('admin.categories.partials.form', ['action' => route('admin.categories.update', $category), 'method' => 'PUT', 'category' => $category])
</div>
@endsection
