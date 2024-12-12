<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropTables extends Command
{
    protected $signature = 'db:drop-tables';
    protected $description = 'Drop specific tables from the database';

    public function handle()
    {
        // Drop specific tables
        DB::statement('DROP TABLE IF EXISTS inventory_batches');
        DB::statement('DROP TABLE IF EXISTS batch_items');

        $this->info('Tables dropped successfully!');
    }
}
