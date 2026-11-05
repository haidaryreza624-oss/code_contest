<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
     public function index()
    {
        $logs = AdminAuditLog::with('admin')
            ->latest()
            ->paginate(1);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
