<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;

class TrackingController extends Controller
{
    public function show(string $invoiceNumber)
    {
        $order = Order::with(['customer', 'statuses.user'])
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$order) {
            abort(404, 'Order laundry tidak ditemukan.');
        }

        return view('tracking.show', compact('order'));
    }
}
