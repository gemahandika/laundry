<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Hak Akses Karyawan') }}
            </h2>

            <button onclick="document.getElementById('modalTambahUser').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md shadow-emerald-100 hover:shadow-lg transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User Baru
            </button>
        </div>
    </x-slot>

    <div class="py-4">

        @if (session('success'))
            <div
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative shadow-sm text-sm">
                <span class="font-semibold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative shadow-sm text-sm">
                <span class="font-semibold">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Nama Karyawan</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Email Login</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Tingkat Hak Akses
                                (Role)</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 text-center uppercase">Aksi
                                Pemutus</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 text-center uppercase">Reset
                                Password</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                <td class="py-3.5 px-4 text-sm font-semibold text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase text-xs border border-blue-100">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-sm text-gray-600 font-mono">{{ $user->email }}</td>

                                <td class="py-3.5 px-4 text-sm">
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.update', $user->id) }}" method="POST"
                                            id="form-role-{{ $user->id }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="role"
                                                onchange="document.getElementById('form-role-{{ $user->id }}').submit()"
                                                class="text-xs font-bold rounded-lg border py-1 px-2.5 cursor-pointer transition focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                                {{ $user->role == 'admin' ? 'bg-purple-50 text-purple-800 border-purple-200' : 'bg-orange-50 text-orange-800 border-orange-200' }}">
                                                <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>
                                                    KASIR (Akses Terbatas)</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                    ADMIN (Akses Penuh)</option>
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold bg-purple-50 text-purple-800 rounded-lg border border-purple-200 uppercase tracking-wide">
                                            👑 ADMIN (Akun Anda)
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-sm text-center">
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mencabut hak akses karyawan {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic font-medium">Sedang Digunakan</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-sm text-center">
                                    <form method="POST" action="{{ route('users.reset-password', $user->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 transition ease-in-out duration-150 shadow-sm"
                                            onclick="return confirm('Yakin ingin mereset password user ini ke 12345678?')">
                                            Reset Password
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 text-sm">
                                    Belum ada data user karyawan terdaftar di sistem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($users, 'hasPages') && $users->hasPages())
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="modalTambahUser"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div
            class="bg-white rounded-xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden transform transition-all duration-150 animate-in fade-in zoom-in-95">

            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-1.5 text-base">
                    👤 Registrasi Karyawan Baru
                </h3>
                <button onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700 font-bold text-2xl transition leading-none">&times;</button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nama Lengkap
                        Karyawan</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                        class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Alamat Email
                        Login</label>
                    <input type="email" name="email" required placeholder="budi@laundry.com"
                        class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Kata Sandi /
                        Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                        class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Hak Akses
                        Awal</label>
                    <select name="role" required
                        class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 cursor-pointer">
                        <option value="kasir" selected>KASIR (Akses Terbatas)</option>
                        <option value="admin">ADMIN (Akses Penuh)</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 mt-5">
                    <button type="button" onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                        Simpan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
