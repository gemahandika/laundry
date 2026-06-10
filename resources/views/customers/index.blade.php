<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pelanggan') }}
            </h2>
            <a href="{{ route('customers.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                ➕ Tambah Pelanggan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <form action="{{ route('customers.index') }}" method="GET" class="flex gap-2 max-w-md">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, no. telp, atau alamat..."
                        class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-9">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit"
                    class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('customers.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-700 border-b border-gray-200">
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100">Nama</th>
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100">No. Telepon</th>
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100">Alamat</th>
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100 text-center">Member</th>
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100 text-center">Detail</th>
                            <th class="py-3 px-4 font-semibold uppercase text-sm text-gray-100 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-900 font-medium">{{ $customer->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $customer->phone }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $customer->address }}</td>
                                <td class="py-3 px-4 text-sm text-center">
                                    @if ($customer->is_member)
                                        <span
                                            class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Member</span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Biasa</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 text-center">
                                    <a href="{{ route('customers.show', $customer->id) }}"
                                        class="bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold py-1 px-2.5 rounded transition">
                                        🔍 Detail & Riwayat
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm text-center flex justify-center items-center gap-2">
                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm"
                                        title="Ubah Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pelanggan ini? Semua riwayat transaksinya juga mungkin akan terdampak.')"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm"
                                            title="Hapus Pelanggan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500 text-sm">
                                    {{ request('search') ? 'Data pelanggan yang Anda cari tidak ditemukan.' : 'Belum ada data pelanggan.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
