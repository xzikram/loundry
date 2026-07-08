<?php

namespace App\Livewire\Tenant\Orders;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Order;
use App\Enums\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.tenant')]
class OrderListPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function progressStatus($orderId, OrderRepositoryInterface $orderRepo)
    {
        $order = Order::find($orderId);
        if (!$order) return;

        $nextStatus = match ($order->status) {
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
                $orderId, 
                $nextStatus->value, 
                'Status diprogres dari dashboard kasir.', 
                Auth::guard('tenant')->id()
            );
            
            \App\Models\Tenant\ActivityLog::create([
                'description' => 'Status order ' . $order->invoice_number . ' diperbarui ke ' . $nextStatus->label(),
                'causer_id' => Auth::guard('tenant')->id(),
            ]);
        }
    }

    public function cancelOrder($orderId, OrderRepositoryInterface $orderRepo)
    {
        $order = Order::find($orderId);
        if (!$order) return;

        $orderRepo->updateStatus(
            $orderId, 
            OrderStatus::CANCELLED->value, 
            'Pesanan dibatalkan oleh kasir.', 
            Auth::guard('tenant')->id()
        );
    }

    public function render()
    {
        $query = Order::with(['customer', 'creator'])
            ->where(function ($q) {
                $searchEscaped = addcslashes($this->search, '%_');
                $q->where('invoice_number', 'like', '%' . $searchEscaped . '%')
                  ->orWhereHas('customer', function ($sub) use ($searchEscaped) {
                      $sub->where('name', 'like', '%' . $searchEscaped . '%');
                  });
            });

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->latest()->paginate(10);

        return view('livewire.tenant.order-list-page', compact('orders'));
    }
}
