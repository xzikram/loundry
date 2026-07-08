<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Cucian - {{ $order->invoice_number }} | KLIIN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="h-full antialiased bg-[#F8F9FC] text-[#1A1D23]">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="flex items-center justify-center space-x-2">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] flex items-center justify-center shadow-lg shadow-[#1E3A5F]/20">
                    <svg class="h-5 w-5 text-[#D4A853]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/><path d="M10 5a1 1 0 011 1v3.586l2.707 2.707a1 1 0 01-1.414 1.414l-3-3A1 1 0 019 10V6a1 1 0 011-1z"/></svg>
                </div>
                <span class="text-xl font-bold text-[#1E3A5F] tracking-wider">KLIIN</span>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-[#1A1D23] tracking-tight">Status Cucian Anda</h2>
            <p class="mt-1.5 text-sm text-[#8896A6]">Invoice: {{ $order->invoice_number }}</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
            <div class="bg-white border border-[#E2E7EF] p-6 rounded-2xl shadow-xl shadow-[#1E3A5F]/5 space-y-8">
                <!-- Customer info card -->
                <div class="flex items-center justify-between border-b border-[#E2E7EF] pb-4">
                    <div>
                        <p class="text-xs text-[#8896A6] uppercase tracking-wider font-semibold">Pelanggan</p>
                        <h4 class="text-base font-bold text-[#1A1D23] mt-1">{{ $order->customer->name ?? 'Pelanggan Umum' }}</h4>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-[#8896A6] uppercase tracking-wider font-semibold">Estimasi Selesai</p>
                        <h4 class="text-sm font-bold text-[#1A1D23] mt-1">{{ $order->estimated_completion ? $order->estimated_completion->format('d M Y H:i') : '-' }}</h4>
                    </div>
                </div>

                <!-- Status Progress bar -->
                @php
                    $stages = ['pending', 'processing', 'washing', 'drying', 'ironing', 'packing', 'ready', 'completed'];
                    $currentStageIndex = array_search($order->status->value, $stages);
                    $percentage = (($currentStageIndex + 1) / count($stages)) * 100;
                @endphp
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold text-[#8896A6]">
                        <span>Progress Pengerjaan</span>
                        <span class="text-[#D4A853] font-bold">{{ round($percentage) }}%</span>
                    </div>
                    <div class="w-full bg-[#E2E7EF] h-3 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#1E3A5F] via-[#2A5082] to-[#D4A853] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <!-- Timeline -->
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
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white {{ $stLog->status->color() }} shadow-lg">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-semibold text-[#1A1D23]">{{ $stLog->status->label() }}</p>
                                                <p class="text-xs text-[#8896A6] mt-0.5">{{ $stLog->notes }}</p>
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
</body>
</html>
