<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Cucian - {{ $order->invoice_number }} | Spinly</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.05); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }
        .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
    </style>
</head>
<body class="h-full antialiased text-[#1A1D23] bg-[#F8F9FC]">
    <!-- Mobile-first Container -->
    <div class="min-h-full flex flex-col justify-start relative overflow-x-hidden">
        
        <!-- Header Dark Banner -->
        <div class="w-full bg-gradient-to-b from-[#0F1A2E] to-[#1A2D4A] text-white pt-10 pb-20 px-6 relative">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full animate-pulse-glow" style="background: radial-gradient(circle, rgba(212,168,83,0.08) 0%, transparent 70%);"></div>
            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-[#D4A853]/30 to-transparent"></div>
            
            <div class="max-w-md mx-auto flex flex-col items-center text-center space-y-4 relative z-10">
                <!-- Brand logo -->
                <div class="flex items-center space-x-2.5">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-[#D4A853] via-[#E8C97A] to-[#10B981] flex items-center justify-center shadow-lg shadow-[#D4A853]/25 shrink-0">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" d="M12 3a9 9 0 1 0 9 9c0-2-1.5-3.5-3-3s-3 1.5-3 3a3 3 0 1 1-6 0c0-1.5 1.2-3 3-3" />
                            <path d="M12 10.5l.3.7.7.3-.7.3-.3.7-.3-.7-.7-.3.7-.3z" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-[#D4A853] to-[#E8C97A]">Spinly</span>
                </div>
                
                <div class="space-y-1">
                    <h2 class="text-xl font-bold tracking-tight">Status Cucian Anda</h2>
                    <p class="text-xs text-white/50 font-medium">Invoice: <span class="text-[#D4A853] font-bold">{{ $order->invoice_number }}</span></p>
                </div>
            </div>
        </div>

        <!-- Floating Card Area (Overlaps the header banner) -->
        <div class="max-w-md w-full mx-auto px-4 -mt-12 pb-16 relative z-20 animate-fade-in-up">
            <div class="space-y-6">
                
                <!-- Main Status & Info Card -->
                <div class="bg-white border border-[#E2E7EF] rounded-2xl shadow-xl shadow-slate-200/50 p-6 space-y-6">
                    <!-- Customer Details -->
                    <div class="flex items-center justify-between border-b border-[#E2E7EF] pb-4">
                        <div>
                            <p class="text-[10px] text-[#8896A6] uppercase tracking-wider font-semibold">Nama Pelanggan</p>
                            <h4 class="text-base font-bold text-[#1A1D23] mt-0.5">{{ $order->customer->name ?? 'Pelanggan Umum' }}</h4>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-[#8896A6] uppercase tracking-wider font-semibold">Estimasi Selesai</p>
                            <h4 class="text-sm font-bold text-[#1A1D23] mt-0.5">{{ $order->estimated_completion ? $order->estimated_completion->format('d M Y H:i') : '-' }}</h4>
                        </div>
                    </div>

                    <!-- Progress Percentage Tracker -->
                    @php
                        $stages = ['pending', 'processing', 'washing', 'drying', 'ironing', 'packing', 'ready', 'completed'];
                        $currentStageIndex = array_search($order->status->value, $stages);
                        if ($currentStageIndex === false) {
                            $currentStageIndex = 0;
                        }
                        $percentage = (($currentStageIndex + 1) / count($stages)) * 100;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs font-semibold text-[#8896A6]">
                            <span>Progress Pengerjaan</span>
                            <span class="text-[#D4A853] font-bold text-sm">{{ round($percentage) }}%</span>
                        </div>
                        <div class="w-full bg-[#E2E7EF] h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-[#1E3A5F] via-[#2A5082] to-[#D4A853] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Timeline/Status Logs -->
                    <div class="flow-root pt-2">
                        <ul class="-mb-8">
                            @php
                                $statusLogs = $order->statuses()->latest()->get();
                            @endphp
                            @forelse($statusLogs as $stLog)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-[#E2E7EF]" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3.5">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white shadow-md text-white
                                                    {{ $loop->first ? 'bg-gradient-to-br from-[#1E3A5F] to-[#2A5082]' : 'bg-[#8896A6]' }}">
                                                    @if($loop->first)
                                                        <svg class="h-4.5 w-4.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    @else
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-bold {{ $loop->first ? 'text-[#1E3A5F]' : 'text-[#4A5568]' }}">{{ $stLog->status->label() }}</p>
                                                    <p class="text-xs text-[#8896A6] mt-0.5">{{ $stLog->notes }}</p>
                                                </div>
                                                <div class="text-right text-[10px] whitespace-nowrap text-[#8896A6] font-medium">
                                                    <time class="block font-bold">{{ $stLog->created_at->format('H:i') }}</time>
                                                    <span class="block text-[9px] mt-0.5">{{ $stLog->created_at->format('d M') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-4 text-[#8896A6] text-xs">Belum ada pembaruan status pengerjaan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Rincian Item Cucian Card -->
                <div class="bg-white border border-[#E2E7EF] rounded-2xl shadow-xl shadow-slate-200/50 p-6 space-y-4">
                    <h3 class="text-sm font-bold text-[#1A1D23] uppercase tracking-wider border-b border-[#E2E7EF] pb-2">Rincian Cucian</h3>
                    <div class="divide-y divide-[#E2E7EF]">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center py-3.5 first:pt-0 last:pb-0">
                                <div>
                                    <h5 class="text-sm font-semibold text-[#1A1D23]">{{ $item->service_name }}</h5>
                                    <p class="text-xs text-[#8896A6] mt-1">{{ number_format($item->quantity, 2) }} {{ $item->unit }} @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-bold text-[#1A1D23]">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-[#E2E7EF] pt-4 space-y-2.5 text-xs font-semibold text-[#8896A6]">
                        <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>Pajak (11%)</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-rose-500"><span>Diskon</span><span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                        @endif
                        <div class="flex justify-between text-[#1A1D23] text-sm font-bold border-t border-[#E2E7EF] pt-3">
                            <span>Total</span>
                            <span class="text-[#D4A853] text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payment Status Badge -->
                    <div class="pt-3 border-t border-[#E2E7EF] flex justify-between items-center">
                        <span class="text-xs font-bold text-[#8896A6]">Status Pembayaran</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border
                            {{ $order->payment_status === 'paid' 
                                ? 'bg-emerald-50 text-emerald-600 border-emerald-200' 
                                : 'bg-rose-50 text-rose-500 border-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5 {{ $order->payment_status === 'paid' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                        </span>
                    </div>
                </div>

                @if($order->payment_status !== 'paid' && !empty($pakasirProjectSlug))
                    @php
                        $amount = (int) ($order->total - $order->paid_amount);
                        $redirectUrl = request()->fullUrl();
                        $pakasirUrl = "https://app.pakasir.com/pay/{$pakasirProjectSlug}/{$amount}?order_id={$order->invoice_number}&redirect=" . urlencode($redirectUrl);
                        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($pakasirUrl);
                    @endphp
                    <!-- Pakasir Payment Card -->
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl shadow-xl shadow-slate-200/50 p-6 space-y-5">
                        <div class="border-b border-[#E2E7EF] pb-3 text-center">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-50 text-[#1E3A5F] mb-2 text-lg">
                                💳
                            </span>
                            <h3 class="text-sm font-bold text-[#1A1D23] uppercase tracking-wider">Bayar Sekarang via QRIS / VA</h3>
                            <p class="text-xs text-[#8896A6] mt-1">Silakan scan QR Code di bawah menggunakan aplikasi e-wallet Anda atau klik tombol bayar untuk menyelesaikan pembayaran secara digital.</p>
                        </div>
                        
                        <div class="flex flex-col items-center space-y-4">
                            <div class="p-3 bg-white border border-[#E2E7EF] rounded-2xl shadow-sm">
                                <img src="{{ $qrCodeUrl }}" alt="QR Code Pembayaran Pakasir" class="h-44 w-44 object-contain">
                            </div>
                            
                            <a href="{{ $pakasirUrl }}" target="_blank"
                               class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-600/10 transition-all cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Bayar Sekarang
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Laundry Contact Card -->
                @php
                    $outlet = $order->outlet;
                    $outletPhone = $outlet->phone ?? '';
                    // Format phone for wa
                    $outletPhoneClean = preg_replace('/[^0-9]/', '', $outletPhone);
                    if (str_starts_with($outletPhoneClean, '0')) {
                        $outletPhoneClean = '62' . substr($outletPhoneClean, 1);
                    } elseif (str_starts_with($outletPhoneClean, '8')) {
                        $outletPhoneClean = '62' . $outletPhoneClean;
                    }
                    $waOutletUrl = $outletPhoneClean ? "https://wa.me/" . $outletPhoneClean . "?text=" . urlencode("Halo, saya ingin bertanya tentang status cucian saya dengan nomor invoice " . $order->invoice_number) : "#";
                    $laundryName = \App\Models\Tenant\Setting::getValue('laundry_name', tenant('name') ?? 'Spinly Laundry');
                @endphp
                <div class="bg-white border border-[#E2E7EF] rounded-2xl shadow-xl shadow-slate-200/50 p-6 space-y-4 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-[#1E3A5F]/5 text-[#1E3A5F] flex items-center justify-center font-bold text-lg">
                        🏪
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-[#1E3A5F]">{{ $laundryName }}</h4>
                        <p class="text-xs text-[#8896A6]">{{ $outlet->name }}</p>
                        <p class="text-xs text-[#8896A6] px-4">{{ $outlet->address ?? '-' }}</p>
                    </div>
                    
                    @if($outletPhoneClean)
                        <div class="pt-2">
                            <a href="{{ $waOutletUrl }}" target="_blank"
                               class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-[#10B981] to-[#059669] hover:from-[#059669] hover:to-[#10B981] shadow-lg shadow-emerald-500/10 transition-all cursor-pointer">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.458L0 24zm6.27-5.466l.374.222c1.661.985 3.652 1.505 5.69 1.506 5.482 0 9.94-4.458 9.944-9.944.002-2.657-1.03-5.155-2.906-7.03C17.553 1.41 15.061.378 12.01.378c-5.485 0-9.943 4.458-9.947 9.943-.001 2.052.536 4.053 1.554 5.73l.24.397-1.026 3.754 3.844-1.008z"/></svg>
                                Hubungi Laundry
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        
    </div>
</body>
</html>
