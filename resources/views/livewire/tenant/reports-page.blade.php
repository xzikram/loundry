<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Laporan</h1>
            <p class="text-sm text-[#8896A6] mt-1">Analisis keuangan dan performa outlet</p>
        </div>
        <input wire:model.live="filterMonth" type="month" class="px-4 py-2.5 border border-[#E2E7EF] bg-white rounded-xl text-sm text-[#1A1D23] shadow-sm">
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <p class="text-xs font-semibold text-[#8896A6] uppercase tracking-wider">Pendapatan</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <p class="text-xs font-semibold text-[#8896A6] uppercase tracking-wider">Pengeluaran</p>
            <h3 class="text-2xl font-bold text-rose-500 mt-2">Rp {{ number_format($expenses, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <p class="text-xs font-semibold text-[#8896A6] uppercase tracking-wider">Laba Bersih</p>
            <h3 class="text-2xl font-bold {{ $profit >= 0 ? 'text-[#1E3A5F]' : 'text-rose-500' }} mt-2">Rp {{ number_format($profit, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <p class="text-xs font-semibold text-[#8896A6] uppercase tracking-wider">Rata-rata/Order</p>
            <h3 class="text-2xl font-bold text-[#D4A853] mt-2">Rp {{ number_format($avgOrder, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Daily Revenue Chart -->
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <h3 class="text-lg font-bold text-[#1A1D23]">Pendapatan Harian</h3>
            <div class="mt-6 h-48 flex items-end justify-between gap-1 border-b border-[#E2E7EF] pb-2">
                @php $maxDay = count($dailyRevenue) ? max($dailyRevenue) : 1; @endphp
                @for($d = 1; $d <= cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year); $d++)
                    @php $val = $dailyRevenue[$d] ?? 0; $h = $maxDay > 0 ? ($val / $maxDay) * 90 : 0; @endphp
                    <div class="flex flex-col items-center w-full group relative">
                        <div class="bg-gradient-to-t from-[#1E3A5F] to-[#D4A853] w-full max-w-[14px] rounded-t transition-all" style="height: {{ max($h, 3) }}%"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
            <h3 class="text-lg font-bold text-[#1A1D23]">Layanan Terlaris</h3>
            <div class="mt-6 space-y-4">
                @forelse($topServices as $srv)
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-[#1A1D23]">{{ $srv->service_name }}</p>
                            <p class="text-xs text-[#8896A6]">{{ number_format($srv->total_qty, 1) }} unit</p>
                        </div>
                        <span class="text-sm font-bold text-[#1E3A5F]">Rp {{ number_format($srv->total_revenue, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-[#8896A6] text-center py-4">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
        <h3 class="text-lg font-bold text-[#1A1D23]">Pelanggan Teratas</h3>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left">
                <thead><tr class="border-b border-[#E2E7EF] text-[#8896A6] text-xs uppercase tracking-wider"><th class="py-3 pr-4">Pelanggan</th><th class="py-3">Telepon</th><th class="py-3 text-right">Spending Bulan Ini</th></tr></thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm">
                    @forelse($topCustomers as $cust)
                        <tr>
                            <td class="py-3 pr-4 font-semibold text-[#1A1D23]">{{ $cust->name }}</td>
                            <td class="py-3 text-[#8896A6]">{{ $cust->phone }}</td>
                            <td class="py-3 text-right font-bold text-[#D4A853]">Rp {{ number_format($cust->period_spent, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-[#8896A6]">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
