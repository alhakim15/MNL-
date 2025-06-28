<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function showPayment($resi)
    {
        $delivery = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('resi', $resi)
            ->where('sender_name', auth()->user()->name)
            ->firstOrFail();

        // Check if payment is already completed
        if ($delivery->payment_status === 'paid') {
            return redirect()->route('deliveries.history')
                ->with('success', 'Pembayaran untuk pengiriman ini sudah selesai.');
        }

        // Calculate shipping cost if not set
        if (!$delivery->shipping_cost || $delivery->shipping_cost == 0) {
            $shippingCost = $this->midtransService->calculateShippingCost(
                $delivery->weight,
                $delivery->from_city_id,
                $delivery->to_city_id
            );

            $delivery->update(['shipping_cost' => $shippingCost]);
        }

        // Create payment token if not exists
        if (!$delivery->payment_token) {
            try {
                $this->midtransService->createTransaction($delivery);
                $delivery->refresh();
            } catch (\Exception $e) {
                Log::error('Failed to create payment token: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal membuat token pembayaran. Silakan coba lagi.');
            }
        }

        return view('payment.show', compact('delivery'));
    }

    public function notification(Request $request)
    {
        try {
            // Log raw notification data
            Log::info('Midtrans notification received', $request->all());

            $notification = new \Midtrans\Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Midtrans notification parsed', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            $delivery = Delivery::where('resi', $orderId)->first();

            if (!$delivery) {
                Log::error('Delivery not found for order_id: ' . $orderId);
                return response('Delivery not found', 404);
            }

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $delivery->update(['payment_status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $notification->payment_type ?? null
                    ]);
                }
            } else if ($transactionStatus == 'settlement') {
                $delivery->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'payment_type' => $notification->payment_type ?? null
                ]);
            } else if ($transactionStatus == 'pending') {
                $delivery->update(['payment_status' => 'pending']);
            } else if ($transactionStatus == 'deny') {
                $delivery->update(['payment_status' => 'failed']);
            } else if ($transactionStatus == 'expire') {
                $delivery->update(['payment_status' => 'expired']);
            } else if ($transactionStatus == 'cancel') {
                $delivery->update(['payment_status' => 'failed']);
            }

            Log::info('Payment status updated for order: ' . $orderId . ', status: ' . $transactionStatus);

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            Log::error('Payment notification exception: ' . $e->getTraceAsString());
            return response('Error', 500);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        Log::info('Payment finish callback received', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus
        ]);

        if ($orderId) {
            $delivery = Delivery::where('resi', $orderId)->first();

            if ($delivery) {
                // Update payment status based on transaction status
                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    return redirect()->route('payment.dashboard')
                        ->with('success', 'Pembayaran berhasil! Terima kasih atas pembayaran Anda.');
                } else {
                    return redirect()->route('payment.dashboard')
                        ->with('warning', 'Status pembayaran: ' . ucfirst($transactionStatus));
                }
            }
        }

        return redirect()->route('payment.dashboard')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran.');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('deliveries.history')
            ->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
    }

    public function error(Request $request)
    {
        return redirect()->route('deliveries.history')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.');
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status', 'all');

        // Get payment statistics
        $totalDeliveries = Delivery::where('sender_name', $user->name)->count();
        $pendingPayments = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'pending')
            ->count();
        $paidPayments = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'paid')
            ->count();
        $failedPayments = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'failed')
            ->count();
        $totalAmount = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'paid')
            ->sum('shipping_cost');

        // Build query for filtered payments
        $query = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('sender_name', $user->name);

        // Apply status filter
        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        // Get filtered payments
        $recentPayments = $query->orderBy('created_at', 'desc')->get();

        // Get pending payments for quick actions
        $pendingPaymentsList = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('sender_name', $user->name)
            ->where('payment_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.dashboard', compact(
            'totalDeliveries',
            'pendingPayments',
            'paidPayments',
            'failedPayments',
            'totalAmount',
            'recentPayments',
            'pendingPaymentsList',
            'status'
        ));
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status', 'all');

        $query = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('sender_name', $user->name);

        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('payment.history', compact('payments', 'status'));
    }

    public function checkStatus($resi)
    {
        $delivery = Delivery::where('resi', $resi)
            ->where('sender_name', auth()->user()->name)
            ->firstOrFail();

        try {
            // Check status from Midtrans
            $status = \Midtrans\Transaction::status($resi);

            Log::info('Midtrans status check result', [
                'order_id' => $resi,
                'transaction_status' => isset($status['transaction_status']) ? $status['transaction_status'] : 'unknown',
                'payment_type' => isset($status['payment_type']) ? $status['payment_type'] : 'unknown'
            ]);

            // Update local status based on Midtrans response
            if (isset($status['transaction_status'])) {
                $transactionStatus = $status['transaction_status'];

                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $status['payment_type'] ?? null
                    ]);
                } else if ($transactionStatus == 'pending') {
                    $delivery->update(['payment_status' => 'pending']);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                    $delivery->update(['payment_status' => 'failed']);
                } else if ($transactionStatus == 'expire') {
                    $delivery->update(['payment_status' => 'expired']);
                }
            }

            return redirect()->route('payment.show', $resi)
                ->with('success', 'Status pembayaran telah diperbarui dari Midtrans.');
        } catch (\Exception $e) {
            Log::error('Failed to check payment status from Midtrans: ' . $e->getMessage());
            return redirect()->route('payment.show', $resi)
                ->with('error', 'Gagal mengecek status dari Midtrans: ' . $e->getMessage());
        }
    }

    public function dashboardDebug()
    {
        $user = auth()->user();

        // Get payment statistics
        $totalDeliveries = Delivery::where('sender_name', $user->name)->count();
        $pendingPayments = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'pending')
            ->count();
        $paidPayments = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'paid')
            ->count();
        $totalAmount = Delivery::where('sender_name', $user->name)
            ->where('payment_status', 'paid')
            ->sum('shipping_cost');

        // Get recent payments
        $recentPayments = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('sender_name', $user->name)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending payments
        $pendingPaymentsList = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('sender_name', $user->name)
            ->where('payment_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.dashboard-debug', compact(
            'totalDeliveries',
            'pendingPayments',
            'paidPayments',
            'totalAmount',
            'recentPayments',
            'pendingPaymentsList'
        ));
    }
}
