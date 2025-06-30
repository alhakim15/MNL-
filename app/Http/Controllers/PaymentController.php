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
            ->where('user_id', auth()->id())
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
            $paymentType = $notification->payment_type ?? null;

            Log::info('Midtrans notification parsed', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
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
                    Log::info('Payment status updated to pending (challenge) for order: ' . $orderId);
                } else if ($fraudStatus == 'accept') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $paymentType
                    ]);
                    Log::info('Payment status updated to paid (capture/accept) for order: ' . $orderId);
                }
            } else if ($transactionStatus == 'settlement') {
                $delivery->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'payment_type' => $paymentType
                ]);
                Log::info('Payment status updated to paid (settlement) for order: ' . $orderId);
            } else if ($transactionStatus == 'pending') {
                $delivery->update(['payment_status' => 'pending']);
                Log::info('Payment status updated to pending for order: ' . $orderId);
            } else if ($transactionStatus == 'deny') {
                $delivery->update(['payment_status' => 'failed']);
                Log::info('Payment status updated to failed (deny) for order: ' . $orderId);
            } else if ($transactionStatus == 'expire') {
                $delivery->update(['payment_status' => 'expired']);
                Log::info('Payment status updated to expired for order: ' . $orderId);
            } else if ($transactionStatus == 'cancel') {
                $delivery->update(['payment_status' => 'failed']);
                Log::info('Payment status updated to failed (cancel) for order: ' . $orderId);
            }

            // Refresh delivery to get updated status
            $delivery->refresh();

            Log::info('Final payment status for order ' . $orderId . ': ' . $delivery->payment_status);

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            Log::error('Payment notification exception: ' . $e->getTraceAsString());
            return response('Error', 500);
        }
    }



    public function finish(Request $request)
    {
        try {
            // Log the raw request to see what's being sent
            Log::info('Payment finish method called', [
                'all_request_data' => $request->all(),
                'query_string' => $request->getQueryString(),
                'full_url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $transactionStatus = $request->transaction_status;

            Log::info('Payment finish callback received', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'user_authenticated' => auth()->check(),
                'user_name' => auth()->check() ? auth()->user()->name : 'guest'
            ]);

            // If no order_id is provided, redirect to not found
            if (!$orderId) {
                Log::error('No order_id provided in finish callback');
                return view('payment.not-found');
            }

            $delivery = Delivery::where('resi', $orderId)->first();

            if (!$delivery) {
                Log::error('Delivery not found for order_id in finish callback: ' . $orderId);
                return view('payment.not-found');
            }

            Log::info('Delivery found for order: ' . $orderId, [
                'delivery_id' => $delivery->id,
                'sender_name' => $delivery->sender_name,
                'current_payment_status' => $delivery->payment_status
            ]);

            // Always update payment status first based on Midtrans callback or API check
            try {
                $status = \Midtrans\Transaction::status($orderId);
                $statusArray = json_decode(json_encode($status), true);

                Log::info('Midtrans status verification in finish callback', [
                    'order_id' => $orderId,
                    'transaction_status' => $statusArray['transaction_status'] ?? 'unknown',
                    'payment_type' => $statusArray['payment_type'] ?? 'unknown',
                    'fraud_status' => $statusArray['fraud_status'] ?? 'unknown'
                ]);

                if (isset($statusArray['transaction_status'])) {
                    $apiTransactionStatus = $statusArray['transaction_status'];
                    $fraudStatus = $statusArray['fraud_status'] ?? null;

                    if ($apiTransactionStatus === 'settlement') {
                        $delivery->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'payment_type' => $statusArray['payment_type'] ?? 'unknown'
                        ]);
                        Log::info('Payment status updated to paid (settlement) for order: ' . $orderId);
                    } else if ($apiTransactionStatus === 'capture' && $fraudStatus === 'accept') {
                        $delivery->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'payment_type' => $statusArray['payment_type'] ?? 'unknown'
                        ]);
                        Log::info('Payment status updated to paid (capture/accept) for order: ' . $orderId);
                    } else if ($apiTransactionStatus === 'pending') {
                        $delivery->update(['payment_status' => 'pending']);
                        Log::info('Payment status updated to pending for order: ' . $orderId);
                    } else if ($apiTransactionStatus === 'deny' || $apiTransactionStatus === 'cancel') {
                        $delivery->update(['payment_status' => 'failed']);
                        Log::info('Payment status updated to failed for order: ' . $orderId);
                    } else if ($apiTransactionStatus === 'expire') {
                        $delivery->update(['payment_status' => 'expired']);
                        Log::info('Payment status updated to expired for order: ' . $orderId);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to verify payment status from Midtrans: ' . $e->getMessage());
                // Fallback to callback parameters
                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    Log::info('Payment status updated to paid based on callback parameters for order: ' . $orderId);
                }
            }

            // Refresh delivery to get updated status
            $delivery->refresh();

            Log::info('Final delivery status for order: ' . $orderId, [
                'payment_status' => $delivery->payment_status,
                'paid_at' => $delivery->paid_at,
                'payment_type' => $delivery->payment_type
            ]);

            // Now handle the response based on user authentication and ownership
            if (auth()->check() && $delivery->user_id === auth()->id()) {
                // User is authenticated and owns this delivery
                if ($delivery->payment_status === 'paid') {
                    Log::info('Redirecting authenticated user to dashboard with success message');
                    return redirect()->route('payment.dashboard')
                        ->with('success', 'Pembayaran berhasil! Terima kasih atas pembayaran Anda. Pengiriman akan segera diproses.');
                } else {
                    Log::info('Redirecting authenticated user to dashboard with status warning');
                    return redirect()->route('payment.dashboard')
                        ->with('warning', 'Status pembayaran: ' . ucfirst($delivery->payment_status));
                }
            } else {
                // User not authenticated or doesn't own this delivery
                // Show success page if payment is completed, otherwise show not-found
                if ($delivery->payment_status === 'paid') {
                    Log::info('Payment successful, showing success page for order: ' . $orderId);
                    return view('payment.success', ['delivery' => $delivery]);
                } else {
                    Log::info('User not authenticated or not owner, showing not-found page for order: ' . $orderId);
                    return view('payment.not-found', ['delivery' => $delivery]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception in payment finish method: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());

            // Return a safe fallback response
            return view('payment.not-found')->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    public function unfinish(Request $request)
    {
        $orderId = $request->order_id;

        Log::info('Payment unfinish callback received', [
            'order_id' => $orderId,
            'user_authenticated' => auth()->check(),
            'user_name' => auth()->check() ? auth()->user()->name : 'guest'
        ]);

        if ($orderId) {
            $delivery = Delivery::where('resi', $orderId)->first();

            if ($delivery) {
                if (auth()->check() && $delivery->user_id === auth()->id()) {
                    // Check if payment is already completed
                    if ($delivery->payment_status === 'paid') {
                        Log::info('Payment already completed for order (unfinish): ' . $orderId);
                        return view('payment.not-found', ['delivery' => $delivery]);
                    }

                    return redirect()->route('payment.dashboard')
                        ->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
                } else {
                    // User not authenticated or doesn't own this delivery
                    Log::info('User not authenticated or not owner (unfinish), showing not-found page for order: ' . $orderId);
                    return view('payment.not-found', ['delivery' => $delivery]);
                }
            }
        }

        // Default fallback
        return view('payment.not-found');
    }

    public function error(Request $request)
    {
        $orderId = $request->order_id;

        Log::error('Payment error callback received', [
            'order_id' => $orderId,
            'user_authenticated' => auth()->check(),
            'user_name' => auth()->check() ? auth()->user()->name : 'guest'
        ]);

        if ($orderId) {
            $delivery = Delivery::where('resi', $orderId)->first();

            if ($delivery) {
                if (auth()->check() && $delivery->user_id === auth()->id()) {
                    // Check if payment is already completed
                    if ($delivery->payment_status === 'paid') {
                        Log::info('Payment already completed for order (error): ' . $orderId);
                        return view('payment.not-found', ['delivery' => $delivery]);
                    }

                    // Update payment status to failed
                    $delivery->update(['payment_status' => 'failed']);

                    return redirect()->route('payment.dashboard')
                        ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.');
                } else {
                    // User not authenticated or doesn't own this delivery
                    // Update status to failed but show not-found page
                    $delivery->update(['payment_status' => 'failed']);
                    Log::info('User not authenticated or not owner (error), showing not-found page for order: ' . $orderId);
                    return view('payment.not-found', ['delivery' => $delivery]);
                }
            }
        }

        // Default fallback
        return view('payment.not-found');
    }

    public function notFound()
    {
        return view('payment.not-found');
    }

    public function dashboard(Request $request)
    {
        // Check email verification first
        if (is_null(auth()->user()->email_verified_at)) {
            return redirect()->route('verification.notice')->with('warning', 'Silakan verifikasi email Anda terlebih dahulu untuk dapat mengakses dashboard pembayaran.');
        }

        $user = auth()->user();
        $status = $request->get('status', 'all');

        // Get payment statistics
        $totalDeliveries = Delivery::where('user_id', $user->id)->count();
        $pendingPayments = Delivery::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->count();
        $paidPayments = Delivery::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->count();
        $failedPayments = Delivery::where('user_id', $user->id)
            ->where('payment_status', 'failed')
            ->count();
        $totalAmount = Delivery::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('shipping_cost');

        // Build query for filtered payments
        $query = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('user_id', $user->id);

        // Apply status filter
        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        // Get filtered payments
        $recentPayments = $query->orderBy('created_at', 'desc')->get();

        // Get pending payments for quick actions
        $pendingPaymentsList = Delivery::with(['fromCity', 'toCity', 'ship'])
            ->where('user_id', $user->id)
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
            ->where('user_id', $user->id);

        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('payment.history', compact('payments', 'status'));
    }

    public function checkStatus($resi)
    {
        $delivery = Delivery::where('resi', $resi)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            // Check status from Midtrans
            $status = \Midtrans\Transaction::status($resi);

            // Convert stdClass to array for easier access
            $statusArray = json_decode(json_encode($status), true);

            Log::info('Midtrans status check result', [
                'order_id' => $resi,
                'transaction_status' => $statusArray['transaction_status'] ?? 'unknown',
                'payment_type' => $statusArray['payment_type'] ?? 'unknown',
                'fraud_status' => $statusArray['fraud_status'] ?? 'unknown',
                'full_response' => $statusArray
            ]);

            // Update local status based on Midtrans response
            if (isset($statusArray['transaction_status'])) {
                $transactionStatus = $statusArray['transaction_status'];
                $fraudStatus = $statusArray['fraud_status'] ?? null;

                if ($transactionStatus == 'settlement') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $statusArray['payment_type'] ?? null
                    ]);
                } else if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $delivery->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'payment_type' => $statusArray['payment_type'] ?? null
                        ]);
                    } else {
                        $delivery->update(['payment_status' => 'pending']);
                    }
                } else if ($transactionStatus == 'pending') {
                    $delivery->update(['payment_status' => 'pending']);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                    $delivery->update(['payment_status' => 'failed']);
                } else if ($transactionStatus == 'expire') {
                    $delivery->update(['payment_status' => 'expired']);
                }

                $delivery->refresh();

                Log::info('Payment status updated for RESI: ' . $resi . ' to: ' . $delivery->payment_status);
            }

            return redirect()->route('payment.show', $resi)
                ->with('success', 'Status pembayaran telah diperbarui dari Midtrans. Status saat ini: ' . ucfirst($delivery->payment_status));
        } catch (\Exception $e) {
            Log::error('Failed to check payment status from Midtrans: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            return redirect()->route('payment.show', $resi)
                ->with('error', 'Gagal mengecek status dari Midtrans: ' . $e->getMessage());
        }
    }



    public function forceUpdateStatus($resi)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $delivery = Delivery::where('resi', $resi)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            // Get status directly from Midtrans
            $status = \Midtrans\Transaction::status($resi);
            $statusArray = json_decode(json_encode($status), true);

            Log::info('Force update status check for RESI: ' . $resi, $statusArray);

            if (isset($statusArray['transaction_status'])) {
                $transactionStatus = $statusArray['transaction_status'];
                $fraudStatus = $statusArray['fraud_status'] ?? null;

                $oldStatus = $delivery->payment_status;

                if ($transactionStatus == 'settlement') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $statusArray['payment_type'] ?? null
                    ]);
                } else if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
                    $delivery->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'payment_type' => $statusArray['payment_type'] ?? null
                    ]);
                } else if ($transactionStatus == 'pending') {
                    $delivery->update(['payment_status' => 'pending']);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                    $delivery->update(['payment_status' => 'failed']);
                } else if ($transactionStatus == 'expire') {
                    $delivery->update(['payment_status' => 'expired']);
                }

                $delivery->refresh();

                return redirect()->route('payment.dashboard')
                    ->with('success', "Status pembayaran berhasil diperbarui dari '{$oldStatus}' ke '{$delivery->payment_status}' berdasarkan data Midtrans.");
            }

            return redirect()->route('payment.dashboard')
                ->with('warning', 'Tidak dapat memperbarui status pembayaran.');
        } catch (\Exception $e) {
            Log::error('Force update status error: ' . $e->getMessage());
            return redirect()->route('payment.dashboard')
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
