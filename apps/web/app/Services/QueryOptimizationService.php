<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QueryOptimizationService
{
    /**
     * Cache frequently accessed data
     */
    public static function cacheModelData(string $key, callable $callback, int $ttl = 3600)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Optimize queries with proper eager loading
     */
    public static function optimizeWithEagerLoading(Builder $query, array $relations): Builder
    {
        return $query->with($relations);
    }

    /**
     * Use database indexes for better performance
     */
    public static function addIndexes(): void
    {
        // Add composite indexes for common queries
        $indexes = [
            'usage_ledgers' => [
                'user_workspace_date' => ['user_id', 'workspace_id', 'usage_date'],
                'model_usage' => ['model_key', 'usage_date'],
            ],
            'messages' => [
                'chat_created' => ['chat_id', 'created_at'],
                'user_role' => ['user_id', 'role'],
            ],
            'chats' => [
                'workspace_updated' => ['workspace_id', 'last_message_at'],
                'user_workspace' => ['user_id', 'workspace_id'],
            ],
            'image_jobs' => [
                'user_status' => ['user_id', 'status'],
                'workspace_created' => ['workspace_id', 'created_at'],
            ],
        ];

        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $indexName => $columns) {
                try {
                    DB::statement("CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} (".implode(', ', $columns).')');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
        }
    }

    /**
     * Optimize pagination queries
     */
    public static function optimizePagination(Builder $query, int $perPage = 20): Builder
    {
        return $query->select(['*'])
            ->orderBy('id', 'desc')
            ->limit($perPage);
    }

    /**
     * Use database views for complex queries
     */
    public static function createOptimizedViews(): void
    {
        $views = [
            'user_usage_summary' => '
                CREATE OR REPLACE VIEW user_usage_summary AS
                SELECT 
                    u.id as user_id,
                    u.name as user_name,
                    w.id as workspace_id,
                    w.name as workspace_name,
                    COUNT(ul.id) as total_requests,
                    SUM(ul.words_debited) as total_words,
                    SUM(ul.tokens_in) as total_tokens_in,
                    SUM(ul.tokens_out) as total_tokens_out,
                    MAX(ul.usage_date) as last_usage_date
                FROM users u
                LEFT JOIN workspaces w ON u.current_workspace_id = w.id
                LEFT JOIN usage_ledgers ul ON u.id = ul.user_id AND w.id = ul.workspace_id
                GROUP BY u.id, u.name, w.id, w.name
            ',
            'model_usage_stats' => "
                CREATE OR REPLACE VIEW model_usage_stats AS
                SELECT 
                    model_key,
                    COUNT(*) as request_count,
                    SUM(words_debited) as total_words,
                    AVG(words_debited) as avg_words_per_request,
                    SUM(tokens_in) as total_tokens_in,
                    SUM(tokens_out) as total_tokens_out,
                    DATE_TRUNC('day', usage_date) as usage_day
                FROM usage_ledgers
                GROUP BY model_key, DATE_TRUNC('day', usage_date)
            ",
        ];

        foreach ($views as $viewName => $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // View might already exist or there might be a syntax error
                \Log::warning("Failed to create view {$viewName}: ".$e->getMessage());
            }
        }
    }

    /**
     * Optimize image storage with CDN
     */
    public static function optimizeImageStorage(): void
    {
        // Configure S3 for image storage if not already configured
        if (config('filesystems.default') !== 's3') {
            config(['filesystems.default' => 's3']);
        }
    }

    /**
     * Implement query result caching
     */
    public static function cacheQueryResults(string $cacheKey, callable $query, int $ttl = 1800): mixed
    {
        return Cache::remember($cacheKey, $ttl, $query);
    }

    /**
     * Optimize database connections
     */
    public static function optimizeDatabaseConnections(): void
    {
        // Set optimal database connection settings
        config([
            'database.connections.pgsql.options' => [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ],
        ]);
    }

    /**
     * Implement lazy loading for heavy relationships
     */
    public static function lazyLoadRelations(Model $model, array $relations): Model
    {
        foreach ($relations as $relation) {
            if (! $model->relationLoaded($relation)) {
                $model->load($relation);
            }
        }

        return $model;
    }

    /**
     * Use database-level aggregations
     */
    public static function useDatabaseAggregations(Builder $query, array $aggregations): Builder
    {
        $selects = [];
        foreach ($aggregations as $alias => $aggregation) {
            $selects[] = DB::raw("{$aggregation} as {$alias}");
        }

        return $query->select($selects);
    }
}
