<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoringService
{
    private static array $timers = [];

    private static array $queries = [];

    /**
     * Start timing a process
     */
    public static function startTimer(string $name): void
    {
        self::$timers[$name] = microtime(true);
    }

    /**
     * End timing and log the result
     */
    public static function endTimer(string $name, string $context = ''): float
    {
        if (! isset(self::$timers[$name])) {
            return 0;
        }

        $duration = microtime(true) - self::$timers[$name];

        // Log slow operations
        if ($duration > 1.0) {
            Log::warning('Slow operation detected', [
                'operation' => $name,
                'duration' => round($duration, 3),
                'context' => $context,
            ]);
        }

        unset(self::$timers[$name]);

        return $duration;
    }

    /**
     * Monitor database query performance
     */
    public static function monitorQuery(string $query, float $duration): void
    {
        self::$queries[] = [
            'query' => $query,
            'duration' => $duration,
            'timestamp' => now(),
        ];

        // Log slow queries
        if ($duration > 0.5) {
            Log::warning('Slow query detected', [
                'query' => $query,
                'duration' => round($duration, 3),
            ]);
        }

        // Keep only last 100 queries in memory
        if (count(self::$queries) > 100) {
            array_shift(self::$queries);
        }
    }

    /**
     * Get performance metrics
     */
    public static function getMetrics(): array
    {
        $slowQueries = array_filter(self::$queries, fn ($q) => $q['duration'] > 0.5);

        return [
            'total_queries' => count(self::$queries),
            'slow_queries' => count($slowQueries),
            'avg_query_time' => count(self::$queries) > 0
                ? array_sum(array_column(self::$queries, 'duration')) / count(self::$queries)
                : 0,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
        ];
    }

    /**
     * Monitor cache performance
     */
    public static function monitorCache(string $key, bool $hit, float $duration = 0): void
    {
        $metrics = Cache::get('cache_metrics', [
            'hits' => 0,
            'misses' => 0,
            'total_time' => 0,
        ]);

        if ($hit) {
            $metrics['hits']++;
        } else {
            $metrics['misses']++;
        }

        $metrics['total_time'] += $duration;

        Cache::put('cache_metrics', $metrics, 3600);
    }

    /**
     * Get cache performance metrics
     */
    public static function getCacheMetrics(): array
    {
        $metrics = Cache::get('cache_metrics', [
            'hits' => 0,
            'misses' => 0,
            'total_time' => 0,
        ]);

        $total = $metrics['hits'] + $metrics['misses'];

        return [
            'hit_rate' => $total > 0 ? ($metrics['hits'] / $total) * 100 : 0,
            'miss_rate' => $total > 0 ? ($metrics['misses'] / $total) * 100 : 0,
            'avg_time' => $total > 0 ? $metrics['total_time'] / $total : 0,
        ];
    }

    /**
     * Monitor API response times
     */
    public static function monitorApiResponse(string $endpoint, float $duration, int $statusCode): void
    {
        $metrics = Cache::get('api_metrics', []);

        if (! isset($metrics[$endpoint])) {
            $metrics[$endpoint] = [
                'total_requests' => 0,
                'total_time' => 0,
                'status_codes' => [],
            ];
        }

        $metrics[$endpoint]['total_requests']++;
        $metrics[$endpoint]['total_time'] += $duration;

        if (! isset($metrics[$endpoint]['status_codes'][$statusCode])) {
            $metrics[$endpoint]['status_codes'][$statusCode] = 0;
        }
        $metrics[$endpoint]['status_codes'][$statusCode]++;

        Cache::put('api_metrics', $metrics, 3600);
    }

    /**
     * Get API performance metrics
     */
    public static function getApiMetrics(): array
    {
        $metrics = Cache::get('api_metrics', []);

        $result = [];
        foreach ($metrics as $endpoint => $data) {
            $result[$endpoint] = [
                'total_requests' => $data['total_requests'],
                'avg_response_time' => $data['total_requests'] > 0
                    ? $data['total_time'] / $data['total_requests']
                    : 0,
                'status_codes' => $data['status_codes'],
            ];
        }

        return $result;
    }

    /**
     * Clear all performance data
     */
    public static function clearMetrics(): void
    {
        self::$timers = [];
        self::$queries = [];
        Cache::forget('cache_metrics');
        Cache::forget('api_metrics');
    }
}
