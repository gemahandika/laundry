<x-app-layout>
    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-2xl overflow-hidden border border-slate-100">

                <div class="bg-slate-800 p-6">
                    <h2 class="text-lg font-black text-white">EDIT DATA PROMO</h2>
                    <p class="text-sm text-slate-300 mt-1">Perbarui informasi kode promo untuk tetap relevan dengan
                        strategi diskon Anda.</p>
                </div>

                <form action="{{ route('promos.update', $promo->id) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Kode
                            Promo</label>
                        <input type="text" name="code" value="{{ $promo->code }}"
                            class="w-full border-slate-200 rounded-xl shadow-sm focus:border-slate-500 focus:ring-slate-500 uppercase font-mono tracking-wider"
                            required>
                        <p class="mt-1.5 text-[11px] text-slate-400">Masukkan kode unik (contoh: MERDEKA2026). Pastikan
                            tidak ada spasi.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Jenis
                            Potongan</label>
                        <select name="type"
                            class="w-full border-slate-200 rounded-xl shadow-sm focus:border-slate-500 focus:ring-slate-500"
                            required>
                            <option value="percentage" {{ $promo->type == 'percentage' ? 'selected' : '' }}>Persentase
                                (%)</option>
                            <option value="nominal" {{ $promo->type == 'nominal' ? 'selected' : '' }}>Nominal Rupiah
                                (Rp)</option>
                        </select>
                        <p class="mt-1.5 text-[11px] text-slate-400">Pilih 'Persentase' untuk potongan % atau 'Nominal'
                            untuk potongan nominal tetap.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Nilai
                            Potongan</label>
                        <input type="number" name="value" value="{{ $promo->value }}"
                            class="w-full border-slate-200 rounded-xl shadow-sm focus:border-slate-500 focus:ring-slate-500"
                            required>
                        <p class="mt-1.5 text-[11px] text-slate-400">Masukkan angka saja. (Contoh: 10 untuk 10%, atau
                            5000 untuk potongan Rp5.000).</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('promos.index') }}"
                            class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-lg transition-all">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-lg transition-all shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
