<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->latest()->get();
        return view('admin.admin-users.index', compact('admins'));

    }
    public function create()
    {
        return view('admin.admin-users.create');
    }
    public function store(Request $req)
    {
        $validated = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $admin = new User();
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->password = Hash::make($validated['password']);
        $admin->is_admin = true;
        $admin->save();
        AdminAuditLog::record(
            'administrator_created',
            'User',
            $admin->id,
            'Created administrator account: ' . $admin->email
        );

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Administrator account created successfully.');

    }
}
