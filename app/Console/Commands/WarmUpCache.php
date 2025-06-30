<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmUpCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up Redis cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Warming up Redis cache...');

        try {
            // Test Redis connection
            Cache::put('warmup_test', 'Redis is working!', 60);
            $test = Cache::get('warmup_test');

            if ($test === 'Redis is working!') {
                $this->info('✅ Redis connection: OK');
            } else {
                $this->error('❌ Redis connection: Failed');
                return 1;
            }

            // Warm up delivery stats
            CacheService::warmUp();
            $this->info('✅ Basic cache warmed up successfully');

            // Warm up additional caches
            $this->info('🔥 Warming up additional caches...');

            // Warm up infographics
            CacheService::getActiveInfographics();
            $this->line('- Active infographics cached');

            // Warm up monthly stats for current month
            $now = now();
            CacheService::getMonthlyStats($now->month, $now->year);
            $this->line('- Monthly statistics cached');

            // Warm up daily revenue for today
            CacheService::getDailyRevenue($now->toDateString());
            $this->line('- Daily revenue cached');

            // Warm up common shipping costs (sample data)
            $cities = City::limit(5)->get();
            foreach ($cities as $fromCity) {
                foreach ($cities as $toCity) {
                    if ($fromCity->id !== $toCity->id) {
                        CacheService::getShippingCost($fromCity->id, $toCity->id, 1.0);
                    }
                }
            }
            $this->line('- Common shipping costs cached');

            // Show cache status
            $this->info('📊 Cache Statistics:');
            $this->line('- Delivery stats cached');
            $this->line('- Cities list cached');
            $this->line('- Ships list cached');
            $this->line('- Popular routes cached');
            $this->line('- Revenue stats cached');
            $this->line('- Active infographics cached');
            $this->line('- Monthly statistics cached');
            $this->line('- Daily revenue cached');
            $this->line('- Shipping costs cached');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Cache warm-up failed: ' . $e->getMessage());
            return 1;
        }
    }
}
