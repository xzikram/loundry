<?php

namespace App\Livewire\Tenant\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Order;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Customer;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.tenant')]
class ReportsPage extends Component
{
    public string $period = 'month';
    public string $filterMonth = '';

    public function mount() { $this->filterMonth = now()->format('Y-m'); }

    public function render()
    {
        $year = substr($this->filterMonth, 0, 4);
        $month = substr($this->filterMonth, 5, 2);

        // Revenue
        $revenue = Order::whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->where('payment_status', 'paid')->sum('total');
        $expenses = Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $month)->sum('amount');
        $profit = $revenue - $expenses;
        $orderCount = Order::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $avgOrder = $orderCount > 0 ? $revenue / $orderCount : 0;

        // Top services
        $topServices = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->select('order_items.service_name', DB::raw('SUM(order_items.total) as total_revenue'), DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('order_items.service_name')
            ->orderByDesc('total_revenue')
            ->take(5)->get();

        // Top customers
        $topCustomers = Customer::select('customers.id', 'customers.name', 'customers.phone')
            ->join('orders', 'orders.customer_id', '=', 'customers.id')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->where('orders.payment_status', 'paid')
            ->groupBy('customers.id', 'customers.name', 'customers.phone')
            ->selectRaw('SUM(orders.total) as period_spent')
            ->orderByDesc('period_spent')
            ->take(5)->get();

        // Daily revenue chart
        $dailyRevenue = Order::whereYear('created_at', $year)->whereMonth('created_at', $month)
            ->where('payment_status', 'paid')
            ->selectRaw('DAY(created_at) as day, SUM(total) as total')
            ->groupBy('day')->orderBy('day')->pluck('total', 'day')->toArray();

        return view('livewire.tenant.reports-page', compact(
            'revenue',
            'expenses',
            'profit',
            'avgOrder',
            'topServices',
            'topCustomers',
            'dailyRevenue',
            'month',
            'year'
        ));
    }
}
