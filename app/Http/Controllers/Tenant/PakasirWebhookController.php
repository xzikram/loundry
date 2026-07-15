<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;
use App\Models\Tenant\Setting;
use App\Models\Tenant\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log incoming webhook data for debugging
        Log::info('Pakasir webhook received:', $request->all());

        $status = $request->input('status');
        $orderId = $request->input('order_id'); // This is the invoice_number (e.g. INV-2607110001)
        $amount = $request->input('amount');
        $project = $request->input('project');
        $paymentMethod = $request->input('payment_method', 'qris');

        if ($status !== 'completed' || !$orderId || !$amount) {
            return response()->json([
                'status' => 'ignored',
                'message' => 'Status is not completed or incomplete parameters.'
            ], 200);
        }

        // Retrieve tenant project credentials
        $slugSetting = Setting::getValue('pakasir_project_slug');
        $apiKeySetting = Setting::getValue('pakasir_api_key');

        if (!$slugSetting || !$apiKeySetting) {
            Log::error('Pakasir Webhook: Tenant configuration missing.');
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant Pakasir credentials not configured.'
            ], 422);
        }

        // Verify the webhook is not spoofed by querying Pakasir API directly
        try {
            $response = Http::timeout(10)->get('https://app.pakasir.com/api/transactiondetail', [
                'project' => $slugSetting,
                'amount' => (int) $amount,
                'order_id' => $orderId,
                'api_key' => $apiKeySetting,
            ]);

            if ($response->failed()) {
                Log::error('Pakasir Webhook verification failed. HTTP Status: ' . $response->status());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to verify transaction status with Pakasir server.'
                ], 502);
            }

            $verifyData = $response->json();
            $transaction = $verifyData['transaction'] ?? null;

            if ($transaction && isset($transaction['status']) && $transaction['status'] === 'completed') {
                // Find order in this tenant
                $order = Order::where('invoice_number', $orderId)->first();

                if (!$order) {
                    Log::error('Pakasir Webhook: Order not found: ' . $orderId);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Order not found.'
                    ], 404);
                }

                if ($order->payment_status === 'paid') {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Order is already marked as paid.'
                    ]);
                }

                // Update order to paid
                $order->update([
                    'paid_amount' => $order->total,
                    'payment_status' => 'paid',
                ]);

                // Create OrderPayment record
                $order->payments()->create([
                    'amount' => $order->total,
                    'method' => 'pakasir',
                    'reference_number' => $transaction['payment_method'] ?? $paymentMethod,
                    'received_by' => null, // null represents system/webhook
                    'paid_at' => now(),
                ]);

                // Record Activity Log
                ActivityLog::create([
                    'description' => 'Pembayaran order ' . $order->invoice_number . ' otomatis lunas via Webhook Pakasir.',
                    'causer_id' => null,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order paid successfully.'
                ]);
            } else {
                Log::warning('Pakasir Webhook: Transaction verification failed. Returned transaction data: ', $verifyData);
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Transaction verification failed on Pakasir.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Pakasir Webhook processing error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
