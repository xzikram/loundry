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
                    <h1 class="text-3xl font-black tracking-tight text-[#1A1D23]">Dashboard</h1>
                    <p class="text-sm text-[#8896A6] mt-1 font-medium">Ringkasan real-time outlet laundry Anda</p>
                </div>
                <a href="{{ route('tenant.pos') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#10B981] via-[#059669] to-[#047857] hover:from-[#059669] hover:to-[#10B981] transition-all shadow-lg shadow-[#10B981]/20 cursor-pointer group">
                    <svg class="mr-2 h-4 w-4 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Transaksi Baru
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Omzet Card -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#D4A853] via-[#C9963E] to-[#B8862D] rounded-2xl p-6 text-white shadow-xl shadow-[#D4A853]/20 group hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-white/5 rounded-full blur-lg"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase opacity-80">Omzet Hari Ini</span>
                            <div class="p-2 rounded-lg bg-white/15 backdrop-blur-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                            <p class="text-xs mt-1 opacity-75 font-semibold">Total terbayar</p>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#3B82F6] via-[#2563EB] to-[#1D4ED8] rounded-2xl p-6 text-white shadow-xl shadow-[#3B82F6]/20 group hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-white/5 rounded-full blur-lg"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase opacity-80">Order Baru</span>
                            <div class="p-2 rounded-lg bg-white/15 backdrop-blur-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black">{{ $stats['orders'] }}</h3>
                            <p class="text-xs mt-1 opacity-75 font-semibold">Cucian masuk hari ini</p>
                        </div>
                    </div>
                </div>

                <!-- Processing Card -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#F59E0B] via-[#D97706] to-[#B45309] rounded-2xl p-6 text-white shadow-xl shadow-[#F59E0B]/20 group hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-white/5 rounded-full blur-lg"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase opacity-80">Sedang Proses</span>
                            <div class="p-2 rounded-lg bg-white/15 backdrop-blur-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black">{{ $stats['processing'] }}</h3>
                            <p class="text-xs mt-1 opacity-75 font-semibold">Dalam antrean</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Card -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#10B981] via-[#059669] to-[#047857] rounded-2xl p-6 text-white shadow-xl shadow-[#10B981]/20 group hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-white/5 rounded-full blur-lg"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase opacity-80">Selesai</span>
                            <div class="p-2 rounded-lg bg-white/15 backdrop-blur-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black">{{ $stats['completed'] }}</h3>
                            <p class="text-xs mt-1 opacity-75 font-semibold">Siap diambil/dikirim</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid: Chart & Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2 bg-white border border-[#E2E7EF] rounded-2xl p-6 flex flex-col justify-between shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#1A1D23]">Tren Pendapatan</h3>
                            <p class="text-xs text-[#8896A6] font-medium">Statistik omzet mingguan</p>
                        </div>
                        <div class="flex items-center space-x-1.5 text-[10px] font-bold uppercase tracking-wider">
                            <span class="h-2.5 w-2.5 rounded-full bg-gradient-to-r from-[#D4A853] to-[#10B981]"></span>
                            <span class="text-[#8896A6]">Revenue</span>
                        </div>
                    </div>
                    <div class="mt-6 h-64 flex items-end justify-between px-4 pb-2 border-b border-[#E2E7EF]">
                        @foreach($chartData['values'] as $index => $val)
                            <div class="flex flex-col items-center w-full group relative">
                                <div class="absolute bottom-full mb-2 bg-gradient-to-r from-[#0F1A2E] to-[#1A2D4A] text-white text-xs px-3 py-1.5 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold">
                                    Rp {{ number_format($val, 0, ',', '.') }}
                                </div>
                                @php
                                    $max = max($chartData['values']) ?: 1;
                                    $heightPercent = ($val / $max) * 90;
                                @endphp
                                <div class="bg-gradient-to-t from-[#D4A853] via-[#10B981] to-[#059669] w-12 rounded-t-xl transition-all group-hover:shadow-lg group-hover:shadow-[#10B981]/20 group-hover:scale-105" style="height: {{ max($heightPercent, 8) }}%"></div>
                                <span class="text-xs text-[#8896A6] mt-2 font-semibold">{{ $chartData['labels'][$index] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 flex flex-col shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#1A1D23]">Order Terkini</h3>
                            <p class="text-xs text-[#8896A6] font-medium">Transaksi terbaru</p>
                        </div>
                        <a href="{{ route('tenant.orders') }}" class="text-[10px] font-bold text-[#10B981] uppercase tracking-wider hover:text-[#059669] transition-colors">Lihat Semua →</a>
                    </div>
                    <div class="mt-6 flex-1 space-y-4">
                        @forelse($recentOrders as $order)
                            <a href="{{ route('tenant.order-details', $order->id) }}" class="flex items-center justify-between border-b border-[#E2E7EF] pb-3 last:border-b-0 last:pb-0 hover:bg-slate-50 -mx-2 px-2 py-1 rounded-lg transition-colors">
                                <div>
                                    <p class="text-sm font-bold text-[#1A1D23]">{{ $order->invoice_number }}</p>
                                    <p class="text-xs text-[#8896A6]">{{ $order->customer->name ?? 'Pelanggan Umum' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-[#D4A853]">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                    <span class="inline-block text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $order->status->color() }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-[#8896A6] text-sm py-8">
                                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-[#D4A853]/10 to-[#10B981]/10 flex items-center justify-center mb-3">
                                    <svg class="h-8 w-8 text-[#D4A853]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="font-semibold">Belum ada transaksi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
