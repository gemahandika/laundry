<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Backup & Restore Database') }}
            </h2>

            <form action="{{ route('backups.run') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md shadow-emerald-100 hover:shadow-lg transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Buat Backup Manual Now
                </button>
            </form>
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
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider">Daftar File Backup Tersimpan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Anda dapat mengunduh file cadangan ke komputer lokal atau
                    melakukan pemulihan (restore) data instan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Nama File Cadangan
                                (.sql)</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Ukuran</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Waktu Dibuat</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase text-center">Aksi
                                Operasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($files as $file)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                <td class="py-3.5 px-4 text-sm font-medium text-gray-900 font-mono">
                                    📦 {{ $file['name'] }}
                                </td>

                                <td class="py-3.5 px-4 text-sm text-gray-600">{{ $file['size'] }}</td>

                                <td class="py-3.5 px-4 text-sm text-gray-500">{{ $file['date'] }}</td>

                                <td class="py-3.5 px-4 text-sm text-center flex justify-center items-center gap-2">
                                    <a href="{{ route('backups.download', $file['name']) }}"
                                        class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 hover:bg-blue-200 text-xs font-bold py-1.5 px-3 rounded-md transition shadow-sm"
                                        title="Unduh File">
                                        Unduh
                                    </a>

                                    <form action="{{ route('backups.restore') }}" method="POST"
                                        onsubmit="return confirm('PERINGATAN! Melakukan restore akan menimpa data database saat ini dengan data dari file cadangan ini. Lanjutkan?')">
                                        @csrf
                                        <input type="hidden" name="backup_file" value="{{ $file['name'] }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-bold py-1.5 px-3 rounded-md transition shadow-sm"
                                            title="Restore Data">
                                            Restore
                                        </button>
                                    </form>

                                    <form action="{{ route('backups.delete', $file['name']) }}" method="POST"
                                        onsubmit="return confirm('Hapus file backup permanen dari sistem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1.5 px-3 rounded-md transition shadow-sm"
                                            title="Hapus File">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 text-sm">
                                    Belum ada file backup database yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
