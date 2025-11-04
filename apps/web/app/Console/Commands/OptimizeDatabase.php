<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance by adding indexes, creating views, and running maintenance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database optimization...');

        // Add database indexes
        $this->info('Adding database indexes...');
        \App\Services\QueryOptimizationService::addIndexes();
        $this->info('✓ Database indexes added');

        // Create optimized views
        $this->info('Creating optimized views...');
        \App\Services\QueryOptimizationService::createOptimizedViews();
        $this->info('✓ Optimized views created');

        // Optimize database connections
        $this->info('Optimizing database connections...');
        \App\Services\QueryOptimizationService::optimizeDatabaseConnections();
        $this->info('✓ Database connections optimized');

        // Clear and rebuild cache
        $this->info('Clearing application cache...');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        $this->info('✓ Application cache cleared');

        // Run database maintenance
        $this->info('Running database maintenance...');
        \DB::statement('VACUUM ANALYZE');
        $this->info('✓ Database maintenance completed');

        $this->info('Database optimization completed successfully!');
    }
}
