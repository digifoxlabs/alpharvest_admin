<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard.index', [
            'stats' => [
                'users' => User::count(),
                'roles' => Role::where('guard_name', 'web')->count(),
                'permissions' => Permission::where('guard_name', 'web')->count(),
            ],
        ]);
    }
}
