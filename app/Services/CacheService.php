<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Delivery;
use App\Models\City;
use App\Models\Ship;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour
    const DELIVERY_STATS_KEY = 'delivery_stats';
    const CITIES_KEY = 'cities_list';
    const SHIPS_KEY = 'ships_list';
    const POPULAR_ROUTES_KEY = 'popular_routes';
    const REVENUE_STATS_KEY = 'revenue_stats';

    /**
     * Cache delivery statistics
     */
    public static function getDeliveryStats()
    {
        return Cache::remember(self::DELIVERY_STATS_KEY, self::CACHE_TTL, function () {
            return [
                'total' => Delivery::count(),
                'pending' => Delivery::where('payment_status', 'pending')->count(),
                'paid' => Delivery::where('payment_status', 'paid')->count(),
                'failed' => Delivery::where('payment_status', 'failed')->count(),
                'today' => Delivery::whereDate('created_at', today())->count(),
            ];
        });
    }

    /**
     * Cache cities list
     */
    public static function getCities()
    {
        return Cache::remember(self::CITIES_KEY, self::CACHE_TTL * 24, function () {
            return City::orderBy('name')->get();
        });
    }

    /**
     * Cache ships list
     */
    public static function getShips()
    {
        return Cache::remember(self::SHIPS_KEY, self::CACHE_TTL * 24, function () {
            return Ship::orderBy('name')->get();
        });
    }

    /**
     * Cache popular routes
     */
    public static function getPopularRoutes()
    {
        return Cache::remember(self::POPULAR_ROUTES_KEY, self::CACHE_TTL, function () {
            return Delivery::selectRaw('from_city_id, to_city_id, COUNT(*) as count')
                ->with(['fromCity', 'toCity'])
                ->groupBy('from_city_id', 'to_city_id')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
        });
    }

    /**
     * Cache revenue statistics
     */
    public static function getRevenueStats()
    {
        return Cache::remember(self::REVENUE_STATS_KEY, self::CACHE_TTL, function () {
            return [
                'total_revenue' => Delivery::where('payment_status', 'paid')->sum('shipping_cost'),
                'monthly_revenue' => Delivery::where('payment_status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->sum('shipping_cost'),
                'daily_revenue' => Delivery::where('payment_status', 'paid')
                    ->whereDate('created_at', now()->toDateString())
                    ->sum('shipping_cost'),
            ];
        });
    }

    /**
     * Cache shipping cost calculation
     */
    public static function getShippingCost($fromCityId, $toCityId, $weight)
    {
        $key = "shipping_cost_{$fromCityId}_{$toCityId}_{$weight}";

        return Cache::remember($key, self::CACHE_TTL * 24, function () use ($fromCityId, $toCityId, $weight) {
            // Basic calculation: base rate + weight rate
            $baseRate = 50000; // Base rate 50,000 IDR
            $weightRate = 10000; // 10,000 IDR per ton
            $distanceMultiplier = 1; // Could be calculated based on city distance

            // Simple distance calculation
            if ($fromCityId != $toCityId) {
                $distanceMultiplier = 1.5; // Different city = 1.5x multiplier
            }

            $cost = ($baseRate + ($weight * $weightRate)) * $distanceMultiplier;
            return $cost;
        });
    }

    /**
     * Cache payment dashboard statistics
     */
    public static function getPaymentDashboardStats($userName)
    {
        $key = "payment_dashboard_{$userName}";

        return Cache::remember($key, 300, function () use ($userName) { // 5 minutes
            return [
                'total_deliveries' => Delivery::where('sender_name', $userName)->count(),
                'pending_payments' => Delivery::where('sender_name', $userName)
                    ->where('payment_status', 'pending')->count(),
                'paid_payments' => Delivery::where('sender_name', $userName)
                    ->where('payment_status', 'paid')->count(),
                'failed_payments' => Delivery::where('sender_name', $userName)
                    ->where('payment_status', 'failed')->count(),
                'total_amount' => Delivery::where('sender_name', $userName)
                    ->where('payment_status', 'paid')->sum('shipping_cost'),
            ];
        });
    }

    /**
     * Cache ship capacity data
     */
    public static function getShipCapacity($shipId)
    {
        $key = "ship_capacity_{$shipId}";

        return Cache::remember($key, 600, function () use ($shipId) { // 10 minutes
            $ship = Ship::find($shipId);
            if (!$ship) return null;

            $totalWeight = Delivery::where('ship_id', $shipId)->sum('weight');

            return [
                'ship' => $ship,
                'used_weight' => $totalWeight,
                'remaining_weight' => $ship->max_weight - $totalWeight,
                'is_available' => $totalWeight < $ship->max_weight,
            ];
        });
    }

    /**
     * Cache route-based statistics
     */
    public static function getRouteStats()
    {
        return Cache::remember('route_statistics', self::CACHE_TTL, function () {
            return Delivery::selectRaw('from_city_id, to_city_id, COUNT(*) as total_deliveries, AVG(shipping_cost) as avg_cost')
                ->with(['fromCity', 'toCity'])
                ->groupBy('from_city_id', 'to_city_id')
                ->orderByDesc('total_deliveries')
                ->limit(10)
                ->get();
        });
    }

    /**
     * Clear specific cache
     */
    public static function clearCache($keys = [])
    {
        if (empty($keys)) {
            $keys = [
                self::DELIVERY_STATS_KEY,
                self::CITIES_KEY,
                self::SHIPS_KEY,
                self::POPULAR_ROUTES_KEY,
                self::REVENUE_STATS_KEY,
            ];
        }

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear user-specific cache
     */
    public static function clearUserCache($userName)
    {
        Cache::forget("payment_dashboard_{$userName}");
        // Clear related user caches
    }

    /**
     * Clear ship-related cache
     */
    public static function clearShipCache($shipId = null)
    {
        if ($shipId) {
            Cache::forget("ship_capacity_{$shipId}");
        } else {
            // Clear all ship caches
            Cache::forget(self::SHIPS_KEY);
        }
    }

    /**
     * Warm up cache
     */
    public static function warmUp()
    {
        self::getDeliveryStats();
        self::getCities();
        self::getShips();
        self::getPopularRoutes();
        self::getRevenueStats();
    }
}
