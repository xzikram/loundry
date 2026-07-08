<?php

namespace App\Livewire\Tenant\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Tenant\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;

#[Layout('layouts.tenant')]
class DashboardPage extends Component
{
    public array $stats = [];
    public array $chartData = [];
    public Collection $recentOrders;

    public function mount(OrderRepositoryInterface $orderRepo)
    {
        $this->stats = $orderRepo->getTodayStats();
        $this->chartData = $orderRepo->getRevenueChartData('weekly');
        $this->recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return <<<'HTML'
        <div class="space-y-8" wire:poll.30s>
            <!-- Welcome Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Dashboard</h1>
                    <p class="text-sm text-[#8896A6] mt-1">Ringkasan real-time outlet laundry Anda</p>
                </div>
                <a href="{{ route('tenant.pos') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] transition-all shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Transaksi Baru
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#1E3A5F]/5 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[#8896A6] tracking-wider uppercase">Omzet Hari Ini</span>
                        <div class="p-2 rounded-lg bg-[#D4A853]/10 text-[#D4A853]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-[#1A1D23]">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-[#8896A6] mt-1">Total terbayar</p>
                    </div>
                </div>

                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#1E3A5F]/5 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[#8896A6] tracking-wider uppercase">Order Baru</span>
                        <div class="p-2 rounded-lg bg-[#1E3A5F]/10 text-[#1E3A5F]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-[#1A1D23]">{{ $stats['orders'] }}</h3>
                        <p class="text-xs text-[#8896A6] mt-1">Cucian masuk hari ini</p>
                    </div>
                </div>

                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#1E3A5F]/5 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[#8896A6] tracking-wider uppercase">Sedang Proses</span>
                        <div class="p-2 rounded-lg bg-amber-500/10 text-amber-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-[#1A1D23]">{{ $stats['processing'] }}</h3>
                        <p class="text-xs text-[#8896A6] mt-1">Dalam antrean</p>
                    </div>
                </div>

                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 hover:shadow-lg hover:shadow-[#1E3A5F]/5 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[#8896A6] tracking-wider uppercase">Selesai</span>
                        <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-[#1A1D23]">{{ $stats['completed'] }}</h3>
                        <p class="text-xs text-[#8896A6] mt-1">Siap diambil/dikirim</p>
                    </div>
                </div>
            </div>

            <!-- Main Grid: Chart & Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2 bg-white border border-[#E2E7EF] rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-[#1A1D23]">Tren Pendapatan</h3>
                        <p class="text-xs text-[#8896A6]">Statistik omzet mingguan</p>
                    </div>
                    <div class="mt-6 h-64 flex items-end justify-between px-4 pb-2 border-b border-[#E2E7EF]">
                        @foreach($chartData['values'] as $index => $val)
                            <div class="flex flex-col items-center w-full group relative">
                                <div class="absolute bottom-full mb-2 bg-[#1E3A5F] text-white text-xs px-2.5 py-1 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rp {{ number_format($val, 0, ',', '.') }}
                                </div>
                                @php
                                    $max = max($chartData['values']) ?: 1;
                                    $heightPercent = ($val / $max) * 90;
                                @endphp
                                <div class="bg-gradient-to-t from-[#1E3A5F] to-[#D4A853] w-12 rounded-t-lg transition-all group-hover:from-[#2A5082] group-hover:to-[#E8C97A]" style="height: {{ max($heightPercent, 8) }}%"></div>
                                <span class="text-xs text-[#8896A6] mt-2 font-medium">{{ $chartData['labels'][$index] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 flex flex-col">
                    <div>
                        <h3 class="text-lg font-bold text-[#1A1D23]">Order Terkini</h3>
                        <p class="text-xs text-[#8896A6]">Transaksi terbaru</p>
                    </div>
                    <div class="mt-6 flex-1 space-y-4">
                        @forelse($recentOrders as $order)
                            <div class="flex items-center justify-between border-b border-[#E2E7EF] pb-3 last:border-b-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-semibold text-[#1A1D23]">{{ $order->invoice_number }}</p>
                                    <p class="text-xs text-[#8896A6]">{{ $order->customer->name ?? 'Pelanggan Umum' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-[#1A1D23]">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                    <span class="inline-block text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $order->status->color() }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-[#8896A6] text-sm py-8">
                                <svg class="h-12 w-12 text-[#E2E7EF] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p>Belum ada transaksi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
