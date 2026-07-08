<?php

namespace App\Livewire\Tenant\Orders;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Order;
use App\Models\Tenant\ReceiptTemplate;
use App\Models\Tenant\Setting;
use App\Repositories\Contracts\OrderRepositoryInterface;

#[Layout('layouts.print')]
class PrintPreviewPage extends Component
{
    public int $orderId;
    public ?Order $order = null;
    public ?ReceiptTemplate $template = null;

    public function mount($id, OrderRepositoryInterface $orderRepo)
    {
        $this->orderId = (int) $id;
        $this->order = $orderRepo->find($this->orderId);

        if (!$this->order) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        // Fetch active receipt template
        $this->template = ReceiptTemplate::where('is_active', true)->where('is_default', true)->first()
            ?? ReceiptTemplate::where('is_active', true)->first();
    }

    public function render()
    {
        return <<<'HTML'
        <div class="max-w-[320px] mx-auto p-4 bg-white text-black text-xs leading-relaxed space-y-4">
            <!-- Floating Control Buttons (Hidden on Print) -->
            <div class="no-print flex justify-between space-x-3 mb-6 bg-[#F8F9FC] border border-[#E2E7EF] p-2 rounded-xl">
                <a href="{{ route('tenant.order-details', $order->id) }}" class="px-3 py-1.5 bg-white border border-[#E2E7EF] text-[#4A5568] rounded-lg text-xs font-semibold hover:bg-[#F8F9FC] transition-colors">
                    Kembali
                </a>
                <button onclick="window.print()" class="px-3 py-1.5 bg-[#1E3A5F] text-white rounded-lg text-xs font-semibold hover:bg-[#2A5082] transition-colors">
                    Cetak
                </button>
            </div>

            <!-- Receipt Content -->
            <div class="text-center font-bold space-y-1">
                @if($template && $template->header)
                    <pre class="font-mono whitespace-pre-wrap text-center leading-snug">{{ $template->header }}</pre>
                @else
                    <h3 class="text-base uppercase tracking-wider">KLIIN</h3>
                    <p class="font-normal text-[10px]">Premium Laundry Management</p>
                @endif
            </div>

            <!-- Metadata details -->
            <div class="space-y-1 border-t border-b border-black border-dashed py-2 font-mono">
                <p>Invoice : {{ $order->invoice_number }}</p>
                <p>Tanggal : {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p>Kasir   : {{ $order->creator->name ?? 'Staff' }}</p>
                <p>Outlet  : {{ $order->outlet->name ?? 'Main Outlet' }}</p>
                <p>Member  : {{ $order->customer->name ?? 'Pelanggan Umum' }}</p>
                @if($order->rack_location)
                    <p>Rak     : {{ $order->rack_location }}</p>
                @endif
            </div>

            <!-- Line Items -->
            <div class="space-y-2 font-mono">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-black pb-1">
                            <th class="font-bold">Item</th>
                            <th class="text-center font-bold">Qty</th>
                            <th class="text-right font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-1">{{ $item->service_name }}</td>
                                <td class="text-center py-1">{{ number_format($item->quantity, 1) }}</td>
                                <td class="text-right py-1">{{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Calculations -->
            <div class="border-t border-black border-dashed pt-2 space-y-1 text-right font-mono">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pajak (11%):</span>
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold border-t border-black border-dotted pt-1 text-sm">
                    <span>Total Tagihan:</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[11px] pt-1">
                    <span>Terbayar:</span>
                    <span>Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[11px]">
                    <span>Kembali:</span>
                    <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Barcode/QR Mock for customer tracking -->
            <div class="flex flex-col items-center justify-center space-y-2 pt-4 border-t border-black border-dashed">
                <!-- Barcode representation -->
                <div class="h-10 w-full flex items-center justify-center space-x-0.5">
                    @foreach(str_split(substr($order->invoice_number, -6)) as $char)
                        <span class="inline-block h-8 bg-black {{ (int)$char % 2 === 0 ? 'w-1' : 'w-0.5' }}"></span>
                        <span class="inline-block h-8 bg-white w-0.5"></span>
                    @endforeach
                </div>
                <span class="text-[9px] font-mono tracking-wider">{{ $order->invoice_number }}</span>
                <p class="text-[9px] text-center max-w-[200px] text-slate-600">Scan barcode / invoice di atas pada domain outlet Anda untuk memantau status cucian.</p>
            </div>

            <!-- Receipt Footer template -->
            <div class="text-center border-t border-black border-dashed pt-4 font-mono whitespace-pre-wrap leading-normal text-[10px]">
                @if($template && $template->footer)
                    <pre class="font-mono whitespace-pre-wrap text-center">{{ $template->footer }}</pre>
                @else
                    Terima kasih telah mencuci bersama kami!<br>
                    Harap periksa pakaian Anda sebelum meninggalkan toko.
                @endif
            </div>

            <!-- Auto Print Trigger Script -->
            <script>
                window.onload = function() {
                    // Slight delay to ensure content layout matches print setup
                    setTimeout(function() {
                        window.print();
                    }, 500);
                }
            </script>
        </div>
        HTML;
    }
}
