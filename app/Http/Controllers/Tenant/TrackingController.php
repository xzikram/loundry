<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;

class TrackingController extends Controller
{
    public function index()
    {
        $invoiceNumber = request()->query('invoice');
        if ($invoiceNumber) {
            $order = Order::with(['customer', 'statuses.user'])
                ->where('invoice_number', $invoiceNumber)
                ->first();

            if ($order) {
                $pakasirProjectSlug = \App\Models\Tenant\Setting::getValue('pakasir_project_slug');
                return view('tracking.show', compact('order', 'pakasirProjectSlug'));
            }

            return redirect()->back()->with('error', 'Invoice "' . $invoiceNumber . '" tidak ditemukan.');
        }

        return redirect()->route('tenant.landing');
    }

    public function show(string $invoiceNumber)
    {
        $order = Order::with(['customer', 'statuses.user'])
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$order) {
            return redirect()->route('tenant.landing')->with('error', 'Invoice "' . $invoiceNumber . '" tidak ditemukan.');
        }

        $pakasirProjectSlug = \App\Models\Tenant\Setting::getValue('pakasir_project_slug');
        return view('tracking.show', compact('order', 'pakasirProjectSlug'));
    }
}
