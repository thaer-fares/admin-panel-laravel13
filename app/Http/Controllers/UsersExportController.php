<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf as MPdf;

class UsersExportController extends Controller
{
    public function __invoke(Request $request, string $format)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        if (in_array($format, ['xlsx', 'csv'])) {
            return Excel::download(new UsersExport($search, $role, $status), "users.$format");
        }

        $users = User::query()
            ->with('roles')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($role, fn ($q) => $q->whereHas('roles', fn ($q2) => $q2->where('name', $role)))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('is_active', $status === 'active'))
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = MPdf::loadView('exports.users-pdf', ['users' => $users]);

        return $pdf->download('users.pdf');
    }
}
