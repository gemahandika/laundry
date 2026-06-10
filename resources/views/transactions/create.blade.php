<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css"
            rel="stylesheet">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buka Nota Transaksi Baru') }}
        </h2>
    </x-slot>

    <style>
        .ts-wrapper .ts-control {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            border-radius: 0.375rem !important;
            border-color: #d1d5db !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.5) !important;
        }
    </style>

    <div class="py-4">
        @if (session('error'))
            <div class="max-w-7xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto">
            <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm" target="_blank">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3 space-y-4">

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">1. Informasi
                                Pelanggan</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                    <select name="customer_name" id="customer_select"
                                        placeholder="Ketik nama / pilih..." required>
                                        <option value=""></option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->name }}" data-id="{{ $customer->id }}"
                                                data-member="{{ $customer->is_member }}"
                                                data-phone="{{ $customer->phone }}"
                                                data-address="{{ $customer->address }}"
                                                data-order-count="{{ $customer->transactions_count ?? 0 }}">
                                                <!-- Tambahkan ini -->
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="customer_id" id="customer_id">
                                </div>


                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon /
                                        WA</label>
                                    <input type="text" name="customer_phone" id="customer_phone"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                        placeholder="081234xxx" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <input type="text" name="customer_address" id="customer_address"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                        placeholder="Jl. Setia Budi...">
                                </div>
                                <!-- Tambahkan ini tepat di bawah div Nama Pelanggan -->
                                <div id="customer_history_info"
                                    class="mt-2 p-2 bg-blue-50 text-blue-700 rounded-md base hidden">
                                    Riwayat Order: <span id="textOrderCount" class="font-bold">0</span> kali mencuci.
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">2. Layanan Laundry
                                </h3>
                                <button type="button" id="addServiceBtn"
                                    class="bg-gray-800 hover:bg-black text-white text-xs font-bold py-1.5 px-3 rounded shadow transition">
                                    + Tambah Baris
                                </button>
                            </div>

                            <div id="servicesContainer" class="space-y-4">
                                <div class="flex gap-2 items-end service-row">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Jenis
                                            Layanan</label>
                                        <select name="services[0][id]"
                                            class="service-select w-full border-gray-300 rounded-md text-sm" required>
                                            <option value="" data-price="0">-- Pilih Layanan --</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    data-price="{{ $service->price }}">
                                                    {{ $service->name }} (Rp
                                                    {{ number_format($service->price, 0, ',', '.') }}/{{ $service->type == 'kiloan' ? 'kg' : 'pcs' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Pilih Aroma</label>
                                        <select name="aroma_id" class="w-full border-gray-300 rounded-md text-sm">
                                            <option value="">-- Aroma --</option>
                                            @foreach ($aromas as $aroma)
                                                <option value="{{ $aroma->id }}">{{ $aroma->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Qty / Berat</label>
                                        <input type="number" name="services[0][qty]" step="0.1" min="0.1"
                                            value="1"
                                            class="qty-input w-full border-gray-300 rounded-md text-sm text-center"
                                            required>
                                    </div>
                                    <div class="w-32">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Total</label>
                                        <input type="text"
                                            class="item-total w-full border-gray-200 rounded-md text-sm bg-gray-50 text-right font-semibold"
                                            readonly value="Rp 0">
                                    </div>
                                    <button type="button"
                                        class="remove-row-btn text-red-500 hover:text-red-700 font-bold p-2 text-sm hidden">✕</button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">3. Catatan
                                Tambahan</h3>
                            <textarea name="notes" rows="2" placeholder="Contoh: Pakaian luntur dipisah, kaos kaki jangan disatukan..."
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-2 border-blue-100">
                            <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-4">4. Ringkasan Nota
                            </h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold text-gray-900" id="textSubtotal">Rp 0</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Gunakan
                                        Promo/Diskon</label>
                                    <select name="promo_id" id="promoSelect"
                                        class="w-full border-gray-300 rounded-md text-xs">
                                        <option value="" data-type="nominal" data-value="0">-- Tanpa Promo --
                                        </option>
                                        @foreach ($promos as $promo)
                                            <option value="{{ $promo->id }}" data-type="{{ $promo->type }}"
                                                data-value="{{ $promo->value }}">
                                                {{ $promo->code }}
                                                ({{ $promo->type == 'percentage' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex justify-between border-b pb-2 text-red-600">
                                    <span>Potongan Diskon</span>
                                    <span class="font-semibold" id="textDiscount">- Rp 0</span>
                                </div>

                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-base font-bold text-gray-800">Total Bayar</span>
                                    <span class="text-xl font-black text-blue-600" id="textTotalPay">Rp 0</span>
                                </div>

                                <div class="pt-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status
                                        Pembayaran</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label
                                            class="flex items-center justify-center border rounded-md p-2 cursor-pointer bg-red-50 border-red-200 text-red-700 font-semibold"
                                            id="labelBelumBayar">
                                            <input type="radio" name="payment_status" value="belum_bayar" checked
                                                class="sr-only" onchange="togglePaymentLabel()">
                                            Belum Bayar
                                        </label>
                                        <label
                                            class="flex items-center justify-center border rounded-md p-2 cursor-pointer bg-gray-50 border-gray-200 text-gray-600 font-semibold"
                                            id="labelLunas">
                                            <input type="radio" name="payment_status" value="lunas"
                                                class="sr-only" onchange="togglePaymentLabel()">
                                            Lunas
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded shadow transition text-center text-sm">
                                    Simpan & Cetak Nota
                                </button>
                                <a href="{{ route('transactions.index') }}"
                                    class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded text-center text-xs transition">
                                    Kembali
                                </a>
                            </div>

                            <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
                            <input type="hidden" name="discount" id="finalDiscountValue" value="0">
                            <input type="hidden" name="total_pay" id="inputTotalPay" value="0">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ==========================================
            // 1. INISIALISASI & LOGIKA TOM SELECT PELANGGAN
            // ==========================================
            const customerSelect = new TomSelect("#customer_select", {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                dropdownParent: 'body', // <-- TAMBAHKAN BARIS INI
                createFilter: function(input) {
                    return input.trim().length >= 2;
                },
                onChange: function(value) {
                    const elId = document.getElementById('customer_id');
                    const elPhone = document.getElementById('customer_phone');
                    const elAddress = document.getElementById('customer_address');
                    const elHistoryInfo = document.getElementById('customer_history_info');
                    const elOrderCount = document.getElementById('textOrderCount');

                    const option = Array.from(document.querySelectorAll('#customer_select option'))
                        .find(opt => opt.value === value);

                    if (option && option.getAttribute('data-id')) {
                        elId.value = option.getAttribute('data-id');
                        elPhone.value = option.getAttribute('data-phone') || '';
                        elAddress.value = option.getAttribute('data-address') || '';

                        const orderCount = option.getAttribute('data-order-count') || 0;
                        elOrderCount.innerText = orderCount;
                        elHistoryInfo.classList.remove('hidden');

                        elPhone.readOnly = true;
                        elAddress.readOnly = true;
                    } else {
                        elId.value = '';
                        elPhone.value = '';
                        elAddress.value = '';
                        elHistoryInfo.classList.add('hidden');
                        elPhone.readOnly = false;
                        elAddress.readOnly = false;
                    }

                    // TAMBAHKAN BARIS INI AGAR DISKON OTOMATIS MUNCUL SAAT PILIH PELANGGAN:
                    calculateTotal();
                }
            });

            // ==========================================
            // 2. LOGIKA DINAMIS TAMBAH/HAPUS BARIS LAYANAN
            // ==========================================
            let rowIndex = 1;

            document.getElementById('addServiceBtn').addEventListener('click', function() {
                const container = document.getElementById('servicesContainer');
                const masterRow = container.querySelector('.service-row').cloneNode(true);

                masterRow.querySelector('.service-select').name = `services[${rowIndex}][id]`;
                masterRow.querySelector('.service-select').value = "";
                masterRow.querySelector('.qty-input').name = `services[${rowIndex}][qty]`;
                masterRow.querySelector('.qty-input').value = 1;
                masterRow.querySelector('.item-total').value = "Rp 0";

                const removeBtn = masterRow.querySelector('.remove-row-btn');
                removeBtn.classList.remove('hidden');
                removeBtn.addEventListener('click', function() {
                    masterRow.remove();
                    calculateTotal();
                });

                container.appendChild(masterRow);
                rowIndex++;
                bindRowEvents(masterRow);
            });

            function bindRowEvents(row) {
                const select = row.querySelector('.service-select');
                const qtyInput = row.querySelector('.qty-input');

                select.addEventListener('change', calculateTotal);
                qtyInput.addEventListener('input', calculateTotal);
            }

            // Jalankan event binding untuk baris pertama bawaan template
            bindRowEvents(document.querySelector('.service-row'));
            document.getElementById('promoSelect').addEventListener('change', calculateTotal);
        });

        // ==========================================
        // 3. LOGIKA HITUNG MATEMATIKA NOTA & PROMO
        // ==========================================
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function calculateTotal() {
            let subtotal = 0;

            // 1. Hitung Subtotal dari baris layanan
            document.querySelectorAll('.service-row').forEach(row => {
                const select = row.querySelector('.service-select');
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-price')) || 0;

                let itemTotal = price * qty;
                subtotal += itemTotal;
                row.querySelector('.item-total').value = formatRupiah(itemTotal);
            });

            // 2. LOGIKA DISKON MEMBER (10%)
            const custSelect = document.getElementById('customer_select');
            // Ambil instance TomSelect
            const ts = custSelect.tomselect;
            // Ambil data dari item yang terpilih via API TomSelect
            const selectedValue = ts.getValue();
            const selectedOption = custSelect.options[custSelect.selectedIndex];

            // Cek apakah option ada dan apakah member
            const isMember = (selectedOption && selectedOption.getAttribute('data-member') == 1);
            let memberDiscount = isMember ? (subtotal * 0.10) : 0;

            // 3. Hitung Promo
            const promoSelect = document.getElementById('promoSelect');
            const selectedPromo = promoSelect.options[promoSelect.selectedIndex];
            const promoValue = parseFloat(selectedPromo.getAttribute('data-value')) || 0;
            const promoType = selectedPromo.getAttribute('data-type');

            let promoDiscount = (promoType === 'percentage') ? (subtotal * promoValue) / 100 : promoValue;

            // 4. Kalkulasi Akhir
            let totalDiscount = promoDiscount + memberDiscount;
            let totalPay = Math.max(0, subtotal - totalDiscount);

            // UPDATE UI (Bagian ini yang krusial)
            document.getElementById('textSubtotal').innerText = formatRupiah(subtotal);
            document.getElementById('textDiscount').innerText = '- ' + formatRupiah(totalDiscount);
            document.getElementById('textTotalPay').innerText = formatRupiah(totalPay);

            // UPDATE INPUT HIDDEN (Agar saat disubmit ke Controller, nilainya benar)
            document.getElementById('inputSubtotal').value = subtotal;
            document.getElementById('finalDiscountValue').value = totalDiscount;
            document.getElementById('inputTotalPay').value = totalPay;
        }

        // ==========================================
        // 4. LOGIKA INTERAKSI WARNA STATUS BAYAR
        // ==========================================
        function togglePaymentLabel() {
            const radios = document.getElementsByName('payment_status');
            const labelBelum = document.getElementById('labelBelumBayar');
            const labelLunas = document.getElementById('labelLunas');

            if (radios[0].checked) {
                labelBelum.className =
                    "flex items-center justify-center border rounded-md p-2 cursor-pointer bg-red-50 border-red-200 text-red-700 font-semibold";
                labelLunas.className =
                    "flex items-center justify-center border rounded-md p-2 cursor-pointer bg-gray-50 border-gray-200 text-gray-600 font-semibold";
            } else {
                labelBelum.className =
                    "flex items-center justify-center border rounded-md p-2 cursor-pointer bg-gray-50 border-gray-200 text-gray-600 font-semibold";
                labelLunas.className =
                    "flex items-center justify-center border rounded-md p-2 cursor-pointer bg-green-50 border-green-200 text-green-700 font-semibold";
            }
        }
    </script>
</x-app-layout>
