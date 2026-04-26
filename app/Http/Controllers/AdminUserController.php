<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $users = User::query()
            ->withCount('bookings')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role && $role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $roleCounts = User::query()
            ->selectRaw("role, count(*) as total")
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalCount = User::query()->count();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'roleFilter' => $role ?: 'all',
            'statusFilter' => $status ?: 'all',
            'roleCounts' => $roleCounts,
            'totalCount' => $totalCount,
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role && $role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->get();

        $lines = [
            ['Name', 'Email', 'Role', 'Status', 'Joined'],
        ];

        foreach ($users as $user) {
            $lines[] = [
                $user->name,
                $user->email,
                $user->role,
                $user->status,
                optional($user->created_at)->format('M d, Y'),
            ];
        }

        $output = collect($lines)->map(function ($row) {
            return collect($row)->map(function ($value) {
                $escaped = str_replace('"', '""', (string) $value);
                return '"' . $escaped . '"';
            })->implode(',');
        })->implode("\n");

        return response($output)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users.csv"');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,agency,customer,user'],
            'status' => ['required', 'in:active,pending,suspended'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $role = $data['role'] === 'user' ? 'customer' : $data['role'];

        $password = $data['password'] ?? Str::random(12);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $role,
            'status' => $data['status'],
            'is_admin' => $role === 'admin',
            'password' => Hash::make($password),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,agency,customer,user'],
            'status' => ['required', 'in:active,pending,suspended'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $role = $data['role'] === 'user' ? 'customer' : $data['role'];

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $role,
            'status' => $data['status'],
            'is_admin' => $role === 'admin',
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}
