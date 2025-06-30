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
    const INFOGRAPHICS_KEY = 'infographics_active';
    const TRACKING_KEY = 'tracking_';
    const USER_DELIVERY_HISTORY_KEY = 'user_deliveries_';
    const DAILY_REVENUE_KEY = 'daily_revenue_';
    const MONTHLY_STATS_KEY = 'monthly_stats_';

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
     * Cache active infographics
     */
    public static function getActiveInfographics()
    {
        return Cache::remember(self::INFOGRAPHICS_KEY, self::CACHE_TTL * 24, function () {
            return \App\Models\Infographic::where('status', 'active')
                ->orderBy('id')
                ->get();
        });
    }

    /**
     * Cache tracking information
     */
    public static function getTrackingInfo($trackingNumber)
    {
        $key = self::TRACKING_KEY . $trackingNumber;

        return Cache::remember($key, 300, function () use ($trackingNumber) { // 5 minutes
            $delivery = Delivery::with(['fromCity', 'toCity', 'ship', 'user'])
                ->where('resi', $trackingNumber)
                ->first();

            if (!$delivery) return null;

            return [
                'delivery' => $delivery,
                'estimated_arrival' => $delivery->created_at->addDays(3), // Example estimation
            ];
        });
    }

    /**
     * Cache user delivery history
     */
    public static function getUserDeliveryHistory($userId, $limit = 10)
    {
        $key = self::USER_DELIVERY_HISTORY_KEY . $userId . '_' . $limit;

        return Cache::remember($key, 600, function () use ($userId, $limit) { // 10 minutes
            return Delivery::where('user_id', $userId)
                ->with(['fromCity', 'toCity', 'ship'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cache daily revenue by date
     */
    public static function getDailyRevenue($date)
    {
        $key = self::DAILY_REVENUE_KEY . $date;

        return Cache::remember($key, 1800, function () use ($date) { // 30 minutes
            return Delivery::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('shipping_cost');
        });
    }

    /**
     * Cache monthly statistics
     */
    public static function getMonthlyStats($month, $year)
    {
        $key = self::MONTHLY_STATS_KEY . $month . '_' . $year;

        return Cache::remember($key, 3600, function () use ($month, $year) { // 1 hour
            $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            return [
                'total_deliveries' => Delivery::whereBetween('created_at', [$startDate, $endDate])->count(),
                'paid_deliveries' => Delivery::whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid')->count(),
                'total_revenue' => Delivery::whereBetween('created_at', [$startDate, $endDate])
                    ->where('payment_status', 'paid')->sum('shipping_cost'),
                'average_weight' => Delivery::whereBetween('created_at', [$startDate, $endDate])->avg('weight'),
                'popular_routes' => Delivery::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('from_city_id, to_city_id, COUNT(*) as count')
                    ->groupBy('from_city_id', 'to_city_id')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),
            ];
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
    public static function clearUserCache($userIdOrName)
    {
        // Clear payment dashboard cache (by username)
        if (is_string($userIdOrName)) {
            Cache::forget("payment_dashboard_{$userIdOrName}");
        }

        // Clear user delivery history cache (by user ID)
        if (is_numeric($userIdOrName)) {
            $userId = $userIdOrName;
            // Clear user delivery history cache
            for ($i = 5; $i <= 50; $i += 5) {
                Cache::forget(self::USER_DELIVERY_HISTORY_KEY . $userId . '_' . $i);
            }
        }
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
     * Clear tracking cache
     */
    public static function clearTrackingCache($trackingNumber)
    {
        Cache::forget(self::TRACKING_KEY . $trackingNumber);
    }

    /**
     * Clear infographics cache
     */
    public static function clearInfographicsCache()
    {
        Cache::forget(self::INFOGRAPHICS_KEY);
    }

    /**
     * Clear delivery-related caches when delivery is created/updated
     */
    public static function clearDeliveryRelatedCache($delivery = null)
    {
        // Clear general stats
        Cache::forget(self::DELIVERY_STATS_KEY);
        Cache::forget(self::POPULAR_ROUTES_KEY);
        Cache::forget(self::REVENUE_STATS_KEY);

        if ($delivery) {
            // Clear specific tracking cache
            self::clearTrackingCache($delivery->resi);

            // Clear user cache
            self::clearUserCache($delivery->user_id);

            // Clear ship capacity cache
            self::clearShipCache($delivery->ship_id);

            // Clear daily revenue cache
            $date = $delivery->created_at->toDateString();
            Cache::forget(self::DAILY_REVENUE_KEY . $date);

            // Clear monthly stats cache
            $month = $delivery->created_at->month;
            $year = $delivery->created_at->year;
            Cache::forget(self::MONTHLY_STATS_KEY . $month . '_' . $year);
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
