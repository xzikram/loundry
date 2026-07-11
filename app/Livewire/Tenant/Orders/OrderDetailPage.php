<?php

namespace App\Livewire\Tenant\Orders;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Order;
use App\Enums\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.tenant')]
class OrderDetailPage extends Component
{
    public int $orderId;
    public ?Order $order = null;
    public string $rackLocation = '';

    public function mount($id, OrderRepositoryInterface $orderRepo)
    {
        $this->orderId = (int) $id;
        $this->order = $orderRepo->find($this->orderId);

        if (!$this->order) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        $this->rackLocation = $this->order->rack_location ?? '';
    }

    public function progressStatus(OrderRepositoryInterface $orderRepo)
    {
        // Progress status state mapping
        $nextStatus = match ($this->order->status) {
            OrderStatus::PENDING => OrderStatus::PROCESSING,
            OrderStatus::PROCESSING => OrderStatus::WASHING,
            OrderStatus::WASHING => OrderStatus::DRYING,
            OrderStatus::DRYING => OrderStatus::IRONING,
            OrderStatus::IRONING => OrderStatus::PACKING,
            OrderStatus::PACKING => OrderStatus::READY,
            OrderStatus::READY => OrderStatus::COMPLETED,
            default => null
        };

        if ($nextStatus) {
            $orderRepo->updateStatus(
                $this->orderId, 
                $nextStatus->value, 
                'Status diprogres dari detail transaksi.', 
                Auth::guard('tenant')->id()
            );

            // Re-fetch order
            $this->order = $orderRepo->find($this->orderId);

            // Log activity log
            \App\Models\Tenant\ActivityLog::create([
                'description' => 'Status order ' . $this->order->invoice_number . ' diperbarui ke ' . $nextStatus->label(),
                'causer_id' => Auth::guard('tenant')->id(),
            ]);
        }
    }

    public function saveRackLocation()
    {
        $this->order->update([
            'rack_location' => $this->rackLocation,
        ]);

        // Log tenant activity log
        \App\Models\Tenant\ActivityLog::create([
            'description' => 'Lokasi rak penyimpanan order ' . $this->order->invoice_number . ' diperbarui ke: ' . $this->rackLocation,
            'causer_id' => Auth::guard('tenant')->id(),
        ]);

        session()->flash('message', 'Lokasi rak berhasil disimpan.');
    }

    public function sendWhatsAppNotification()
    {
        $phone = $this->order->customer->phone ?? '';
        if (!$phone) {
            session()->flash('wa_message', 'Gagal: Nomor WhatsApp pelanggan tidak ditemukan.');
            return;
        }

        // Format phone: remove characters, convert 08/8 to 62
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        $trackingUrl = route('tenant.track', ['invoice_number' => $this->order->invoice_number]);
        
        $statusLabel = match ($this->order->status) {
            OrderStatus::PENDING => 'diterima dan masuk antrean pengerjaan',
            OrderStatus::PROCESSING => 'mulai diproses',
            OrderStatus::WASHING => 'sedang dicuci',
            OrderStatus::DRYING => 'sedang dikeringkan',
            OrderStatus::IRONING => 'sedang disetrika',
            OrderStatus::PACKING => 'sedang dipacking',
            OrderStatus::READY => 'SELESAI dan SIAP DIAMBIL/DIANTAR',
            OrderStatus::PICKED_UP => 'telah diambil',
            OrderStatus::DELIVERED => 'telah diantar',
            OrderStatus::COMPLETED => 'selesai sepenuhnya dan telah diserahkan',
            OrderStatus::CANCELLED => 'dibatalkan',
            default => 'diperbarui statusnya menjadi ' . $this->order->status->label()
        };

        $paymentStatusLabel = $this->order->payment_status === 'paid' ? 'LUNAS' : 'BELUM LUNAS';
        $laundryName = \App\Models\Tenant\Setting::getValue('laundry_name', tenant('name') ?? 'Spinly Laundry');

        $message = "Halo *" . ($this->order->customer->name ?? 'Pelanggan') . "*,\n\n"
                 . "Cucian Anda di *" . $laundryName . "* dengan nomor invoice *" . $this->order->invoice_number . "* saat ini *" . $statusLabel . "*! 🎉\n\n"
                 . "*Rincian Tagihan:*\n"
                 . "- Total: *Rp " . number_format($this->order->total, 0, ',', '.') . "*\n"
                 . "- Status Pembayaran: *" . $paymentStatusLabel . "*\n\n"
                 . "Anda dapat memantau status proses pengerjaan cucian Anda secara real-time melalui tautan berikut:\n"
                 . $trackingUrl . "\n\n"
                 . "Terima kasih telah mempercayai layanan kami. 🙏";

        $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        // Dispatch browser event to open URL in new tab
        $this->dispatch('open-wa-chat', url: $waUrl);

        // Log tenant activity log
        \App\Models\Tenant\ActivityLog::create([
            'description' => 'Membuka WhatsApp Click-to-Chat untuk notifikasi status ' . $this->order->status->label() . ' ke pelanggan: ' . ($this->order->customer->phone ?? 'Pelanggan'),
            'causer_id' => Auth::guard('tenant')->id(),
        ]);

        session()->flash('wa_message', 'Tautan WhatsApp berhasil dibuat dan siap dikirim!');
    }

    public function render()
    {
        return <<<'HTML'
        <div class="space-y-8" x-data x-init="window.addEventListener('open-wa-chat', event => { window.open(event.detail.url, '_blank'); })">
            <!-- Session Messages -->
            @if(session()->has('message'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif
            @if(session()->has('wa_message'))
                <div class="bg-[#1E3A5F]/5 border border-[#1E3A5F]/15 text-[#1E3A5F] p-4 rounded-xl text-sm flex items-center space-x-2 font-medium">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1E3A5F] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#1E3A5F]"></span>
                    </span>
                    <span>{{ session('wa_message') }}</span>
                </div>
            @endif

            <!-- Header back button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('tenant.orders') }}" class="p-2 bg-white border border-[#E2E7EF] hover:bg-[#F8F9FC] text-[#8896A6] hover:text-[#1A1D23] rounded-xl transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">{{ $order->invoice_number }}</h1>
                        <p class="text-xs text-[#8896A6] mt-1">Dibuat oleh {{ $order->creator->name ?? 'Kasir' }} pada {{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    @if($order->status !== OrderStatus::CANCELLED)
                        <button wire:click="sendWhatsAppNotification"
                            class="inline-flex items-center px-4 py-2 border border-emerald-200 text-sm font-semibold rounded-xl text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-all cursor-pointer">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Kirim WA
                        </button>
                    @endif
                    <a href="{{ route('tenant.orders.print', $order->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-[#E2E7EF] text-sm font-semibold rounded-xl text-[#4A5568] bg-white hover:bg-[#F8F9FC] transition-all">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak
                    </a>
                    @if($order->status !== OrderStatus::COMPLETED && $order->status !== OrderStatus::CANCELLED)
                        <button wire:click="progressStatus"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] transition-all shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
                            Update Status ({{ $order->status->label() }} →)
                        </button>
                    @endif
                </div>
            </div>

            <!-- Details Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left panel -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Items Card -->
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-4">
                        <h3 class="text-md font-bold text-[#1A1D23]">Rincian Cucian</h3>
                        <div class="divide-y divide-[#E2E7EF]">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center py-4 first:pt-0 last:pb-0">
                                    <div>
                                        <h5 class="text-sm font-semibold text-[#1A1D23]">{{ $item->service_name }}</h5>
                                        <p class="text-xs text-[#8896A6] mt-1">{{ number_format($item->quantity, 2) }} {{ $item->unit }} @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-[#1A1D23]">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-[#E2E7EF] pt-4 space-y-2.5 text-sm font-medium text-[#8896A6]">
                            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span>Pajak (11%)</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between text-[#1A1D23] text-base font-bold border-t border-[#E2E7EF] pt-3">
                                <span>Total Tagihan</span>
                                <span class="text-[#D4A853]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Logs -->
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-4">
                        <h3 class="text-md font-bold text-[#1A1D23]">Riwayat Pembayaran</h3>
                        <div class="space-y-3">
                            @forelse($order->payments as $pmt)
                                <div class="flex justify-between items-center bg-[#F8F9FC] border border-[#E2E7EF] p-3 rounded-xl">
                                    <div>
                                        <p class="text-sm font-semibold text-[#1A1D23] capitalize">Metode: {{ $pmt->method }}</p>
                                        <p class="text-[10px] text-[#8896A6] mt-0.5">Diterima {{ $pmt->paid_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($pmt->amount, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="text-center text-[#8896A6] text-sm py-4">Belum ada catatan pembayaran.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right panel -->
                <div class="space-y-8">
                    <!-- Customer Details -->
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-4">
                        <h3 class="text-md font-bold text-[#1A1D23]">Pelanggan</h3>
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-[#1E3A5F]/10 text-[#1E3A5F] flex items-center justify-center font-bold">
                                {{ strtoupper(substr($order->customer->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-[#1A1D23]">{{ $order->customer->name ?? 'Pelanggan Umum' }}</h4>
                                <p class="text-xs text-[#8896A6]">{{ $order->customer->phone ?? 'No WhatsApp' }}</p>
                            </div>
                        </div>
                        <div class="border-t border-[#E2E7EF] pt-3 text-xs text-[#8896A6]">
                            <p><span class="font-semibold text-[#4A5568]">Alamat:</span> {{ $order->customer->address ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Rack Storage -->
                    @if($order->status === OrderStatus::READY || $order->status === OrderStatus::COMPLETED)
                        <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-4">
                            <h3 class="text-md font-bold text-[#1A1D23]">Lokasi Rak</h3>
                            <div class="space-y-3">
                                <select wire:model="rackLocation" class="w-full px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                                    <option value="">Belum Ditempatkan</option>
                                    <option value="Rak A-1">Rak A-1</option>
                                    <option value="Rak A-2">Rak A-2</option>
                                    <option value="Rak B-1">Rak B-1</option>
                                    <option value="Rak B-2">Rak B-2</option>
                                    <option value="Rak B-3">Rak B-3</option>
                                </select>
                                <button wire:click="saveRackLocation" class="w-full py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white text-xs font-bold rounded-xl transition-all cursor-pointer">
                                    Simpan Lokasi Rak
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Status Timeline -->
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-6">
                        <h3 class="text-md font-bold text-[#1A1D23]">Timeline Cucian</h3>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($order->statuses as $stLog)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-[#E2E7EF]" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white {{ $stLog->status->color() }}">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-xs font-bold text-[#1A1D23]">{{ $stLog->status->label() }}</p>
                                                        <p class="text-[10px] text-[#8896A6] mt-0.5">{{ $stLog->notes }}</p>
                                                    </div>
                                                    <div class="text-right text-[10px] whitespace-nowrap text-[#8896A6]">
                                                        <time>{{ $stLog->created_at->format('H:i') }}</time>
                                                        <p class="text-[9px]">{{ $stLog->created_at->format('d M') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
