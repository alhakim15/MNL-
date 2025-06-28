<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-app {--user= : Clear cache for specific user} {--ship= : Clear cache for specific ship}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application specific caches with options';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Clearing application caches...');

        try {
            // Clear specific user cache if provided
            if ($this->option('user')) {
                $userName = $this->option('user');
                CacheService::clearUserCache($userName);
                $this->info("✅ Cleared cache for user: {$userName}");
                return 0;
            }

            // Clear specific ship cache if provided
            if ($this->option('ship')) {
                $shipId = $this->option('ship');
                CacheService::clearShipCache($shipId);
                $this->info("✅ Cleared cache for ship ID: {$shipId}");
                return 0;
            }

            // Clear all application caches
            CacheService::clearCache();

            // Clear additional caches
            Cache::forget('route_statistics');
            Cache::forget('delivery_stats_widget');

            // Clear shipping cost caches (pattern-based)
            try {
                $redis = Cache::getRedis();
                $keys = $redis->keys('*shipping_cost_*');
                foreach ($keys as $key) {
                    Cache::forget($key);
                }
            } catch (\Exception $e) {
                $this->warn('Could not clear shipping cost caches: ' . $e->getMessage());
            }

            $this->info('✅ All application caches cleared successfully');

            $this->info('📋 Cleared caches:');
            $this->line('- Delivery statistics');
            $this->line('- Cities and ships data');
            $this->line('- Popular routes');
            $this->line('- Revenue statistics');
            $this->line('- Route statistics');
            $this->line('- Shipping cost calculations');
            $this->line('- Widget caches');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Cache clear failed: ' . $e->getMessage());
            return 1;
        }
    }
}
