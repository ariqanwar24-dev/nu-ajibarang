<x-admin-layout title="Kelola Pengguna">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">Autentikasi & Akses</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">
                    Kelola Pengguna
                </h1>
                <p class="mt-1.5 max-w-2xl text-sm text-brand-muted">
                    Tinjau pendaftaran baru, setujui atau tolak akun, dan kelola role pengguna.
                </p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-brand">
                {{ auth()->user()->roleLabel() }}
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-[1500px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-800">Menunggu Persetujuan</p>
                <p class="mt-1 text-3xl font-black text-amber-900">{{ $ringkasan['pending'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-800">Akun Aktif</p>
                <p class="mt-1 text-3xl font-black text-emerald-900">{{ $ringkasan['active'] }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-red-800">Ditolak</p>
                <p class="mt-1 text-3xl font-black text-red-900">{{ $ringkasan['rejected'] }}</p>
            </div>
        </section>

        <section class="space-y-3">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Pendaftaran Menunggu</h2>
                    <p class="text-xs text-slate-500">Akun baru belum dapat mengakses fitur anggota sampai disetujui.</p>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $pendingUsers->count() }} pengajuan</span>
            </div>

            @forelse ($pendingUsers as $item)
                <article class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm"
                         x-data="{ rejectOpen: false }">
                    <div class="flex flex-wrap items-start justify-between gap-4 p-5">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-lg font-black text-amber-800">
                                {{ mb_substr($item->name, 0, 1) }}
                            </span>
                            <div class="min-w-0">
                                <h3 class="truncate text-base font-black text-slate-900">{{ $item->name }}</h3>
                                <p class="truncate text-xs text-slate-500">{{ $item->email }}</p>
                                <p class="mt-1 text-[10.5px] font-semibold text-slate-400">
                                    Mendaftar {{ $item->created_at?->format('d-m-Y H:i') ?? '—' }}
                                    @if ($item->anggota)
                                        · Data anggota: {{ $item->anggota->nama_anggota }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-slate-600">
                                {{ $item->roleLabel() }}
                            </span>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-amber-800">
                                Menunggu
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 border-t border-slate-100 bg-slate-50/70 px-5 py-4">
                        <form action="{{ route('admin.pengguna.approve', $item) }}" method="POST" class="inline-flex">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800">
                                Setujui Akun
                            </button>
                        </form>

                        <button type="button" @click="rejectOpen = ! rejectOpen"
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100">
                            Tolak Pendaftaran
                        </button>
                    </div>

                    <div x-show="rejectOpen" x-cloak class="border-t border-red-100 bg-red-50/40 px-5 py-4">
                        <form action="{{ route('admin.pengguna.reject', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label for="catatan-pengguna-{{ $item->id }}"
                                   class="mb-1.5 block text-[10.5px] font-extrabold uppercase tracking-wider text-red-700">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="catatan-pengguna-{{ $item->id }}" name="status_note" rows="2" required maxlength="1000"
                                      placeholder="Jelaskan alasan pendaftaran ditolak..."
                                      class="w-full rounded-xl border-red-200 text-xs leading-relaxed shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('status_note') }}</textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="submit"
                                        class="rounded-xl bg-red-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-red-800">
                                    Kirim Penolakan
                                </button>
                                <button type="button" @click="rejectOpen = false"
                                        class="rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-700 hover:bg-red-50">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-brand">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 13 4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-black text-slate-800">Tidak ada pendaftaran menunggu</h3>
                    <p class="mt-1 text-sm text-slate-500">Semua pendaftaran sudah ditinjau.</p>
                </div>
            @endforelse
        </section>

        @if (auth()->user()->isSuperAdmin())
            <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Manajemen Role</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Hanya super admin yang dapat mengubah role. Role tidak dapat diubah melalui formulir profil.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50/80">
                            <tr class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3">Pengguna</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Role Saat Ini</th>
                                <th class="px-5 py-3 text-right">Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($users as $item)
                                @php
                                    $roleBadge = match ($item->role) {
                                        \App\Models\User::ROLE_SUPER_ADMIN => 'bg-amber-100 text-amber-800',
                                        \App\Models\User::ROLE_ADMIN => 'bg-emerald-100 text-emerald-800',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                    $statusBadge = match ($item->status) {
                                        \App\Models\User::STATUS_ACTIVE => 'bg-emerald-100 text-emerald-800',
                                        \App\Models\User::STATUS_REJECTED => 'bg-red-100 text-red-700',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                <tr class="align-middle">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-extrabold uppercase text-white">
                                                {{ mb_substr($item->name, 0, 1) }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-bold text-slate-800">
                                                    {{ $item->name }}
                                                    @if ($item->is(auth()->user()))
                                                        <span class="ml-1 text-[10px] font-semibold text-slate-400">(Anda)</span>
                                                    @endif
                                                </p>
                                                <p class="truncate text-xs text-slate-500">{{ $item->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $statusBadge }}">
                                            {{ $item->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $roleBadge }}">
                                            {{ $item->roleLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($item->is(auth()->user()))
                                            <p class="text-right text-[11px] font-semibold text-slate-400">
                                                Role sendiri tidak dapat diubah.
                                            </p>
                                        @else
                                            <form action="{{ route('admin.pengguna.role', $item) }}" method="POST"
                                                  class="flex items-center justify-end gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" aria-label="Role {{ $item->name }}"
                                                        class="min-w-[130px] rounded-xl border-slate-200 py-2 pl-3 pr-8 text-xs font-semibold shadow-sm focus:border-brand focus:ring-brand">
                                                    @foreach (\App\Models\User::roles() as $roleValue => $roleLabel)
                                                        <option value="{{ $roleValue }}" @selected($item->role === $roleValue)>
                                                            {{ $roleLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit"
                                                        class="rounded-xl bg-brand px-3 py-2 text-xs font-bold text-white transition hover:bg-brand-dark">
                                                    Simpan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-900">
                Pengelolaan role hanya tersedia untuk super admin. Admin tetap dapat menyetujui atau menolak pendaftaran pengguna.
            </div>
        @endif
    </div>
</x-admin-layout>
