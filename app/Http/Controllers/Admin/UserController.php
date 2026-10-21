<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $ringkasan = [
            'pending' => User::query()->where('status', User::STATUS_PENDING)->count(),
            'active' => User::query()->where('status', User::STATUS_ACTIVE)->count(),
            'rejected' => User::query()->where('status', User::STATUS_REJECTED)->count(),
        ];

        $pendingUsers = User::query()
            ->with('anggota')
            ->where('status', User::STATUS_PENDING)
            ->oldest()
            ->get();

        $users = $request->user()->isSuperAdmin()
            ? User::query()->with('anggota')->orderBy('name')->get()
            : collect();

        return view('admin.pengguna.index', compact('ringkasan', 'pendingUsers', 'users'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('approve', $user);

        $user->status = User::STATUS_ACTIVE;
        $user->status_note = null;
        $user->reviewed_at = now();
        $user->reviewed_by = $request->user()->id;
        $user->save();

        return back()->with('success', 'Akun '.$user->name.' berhasil disetujui.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('reject', $user);

        $validated = $request->validate([
            'status_note' => ['required', 'string', 'max:1000'],
        ], [
            'status_note.required' => 'Alasan penolakan wajib diisi.',
            'status_note.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $user->status = User::STATUS_REJECTED;
        $user->status_note = $validated['status_note'];
        $user->reviewed_at = now();
        $user->reviewed_by = $request->user()->id;
        $user->save();

        return back()->with('success', 'Pendaftaran '.$user->name.' ditolak.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('updateRole', $user);

        $validated = $request->validate([
            'role' => ['required', Rule::in(array_keys(User::roles()))],
        ], [
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        if (
            $user->isSuperAdmin()
            && $validated['role'] !== User::ROLE_SUPER_ADMIN
            && User::query()->where('role', User::ROLE_SUPER_ADMIN)->count() <= 1
        ) {
            return back()->withErrors([
                'role' => 'Super admin terakhir tidak boleh diturunkan rolenya.',
            ]);
        }

        $user->role = $validated['role'];
        $user->is_admin = in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);

        // Mempromosikan pengguna menjadi admin otomatis mengaktifkan akunnya.
        if ($user->role !== User::ROLE_USER && ! $user->isActive()) {
            $user->status = User::STATUS_ACTIVE;
            $user->status_note = null;
            $user->reviewed_at = now();
            $user->reviewed_by = $request->user()->id;
        }

        $user->save();

        return back()->with('success', 'Role '.$user->name.' berhasil diperbarui menjadi '.$user->roleLabel().'.');
    }
}
