<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Delivery;

class UpdatePaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:update-status {resi} {status=paid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment status for a delivery';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resi = $this->argument('resi');
        $status = $this->argument('status');

        $delivery = Delivery::where('resi', $resi)->first();

        if (!$delivery) {
            $this->error("Delivery with resi {$resi} not found!");
            return 1;
        }

        $oldStatus = $delivery->payment_status;

        $updateData = ['payment_status' => $status];
        if ($status === 'paid') {
            $updateData['paid_at'] = now();
            $updateData['payment_type'] = 'manual_update';
        }

        $delivery->update($updateData);

        $this->info("Payment status updated successfully!");
        $this->info("Resi: {$resi}");
        $this->info("Old Status: {$oldStatus}");
        $this->info("New Status: {$status}");

        if ($status === 'paid') {
            $this->info("Paid at: " . $delivery->paid_at->format('Y-m-d H:i:s'));
        }

        return 0;
    }
}
