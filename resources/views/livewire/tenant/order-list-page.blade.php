<div class="space-y-6" wire:poll.10s>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Orders</h1>
            <p class="text-sm text-[#8896A6] mt-1">Daftar transaksi dan pemrosesan cucian</p>
        </div>
    </div>

    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <div class="flex-1 relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari invoice atau nama pelanggan..."
                class="w-full pl-10 pr-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm">
            <div class="absolute left-3.5 top-3 text-[#8896A6]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <select wire:model.live="statusFilter" class="px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Diproses</option>
            <option value="washing">Pencucian</option>
            <option value="drying">Pengeringan</option>
            <option value="ironing">Penyetrikaan</option>
            <option value="packing">Packing</option>
            <option value="ready">Siap Diambil</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4 font-semibold text-[#1A1D23]">
                                <a href="{{ route('tenant.order-details', $order->id) }}" class="hover:text-[#1E3A5F] transition-colors">
                                    {{ $order->invoice_number }}
                                </a>
                                @if($order->priority !== 'regular')
                                    <span class="inline-block text-[9px] font-bold text-[#D4A853] bg-[#D4A853]/10 px-1.5 py-0.5 rounded ml-1 uppercase border border-[#D4A853]/20">
                                        {{ $order->priority }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-[#1A1D23]">{{ $order->customer->name ?? 'Pelanggan Umum' }}</p>
                                <p class="text-xs text-[#8896A6]">{{ $order->customer->phone ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-[#8896A6]">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $order->status->color() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-500 border border-rose-200' }}">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-[#1A1D23]">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($order->status !== \App\Enums\OrderStatus::COMPLETED && $order->status !== \App\Enums\OrderStatus::CANCELLED)
                                    <button wire:click="progressStatus({{ $order->id }})" 
                                        class="px-2.5 py-1 text-xs bg-[#1E3A5F] hover:bg-[#2A5082] text-white font-semibold rounded-lg shadow-sm transition-all cursor-pointer">
                                        @if($order->status === \App\Enums\OrderStatus::READY) Serahkan @else Progres @endif
                                    </button>
                                    <button wire:click="cancelOrder({{ $order->id }})" 
                                        wire:confirm="Yakin batalkan order {{ $order->invoice_number }}?"
                                        class="px-2.5 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 border border-rose-200 rounded-lg transition-all cursor-pointer">
                                        Batal
                                    </button>
                                @else
                                    <span class="text-xs text-[#8896A6] font-medium">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#8896A6]">
                                <svg class="h-12 w-12 text-[#E2E7EF] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Belum ada order laundry masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">
            {{ $orders->links() }}
        </div>
    </div>
</div>
