<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTableExistence extends Command
{
    protected $signature = 'check:table {tableName}';
    protected $description = 'Check if a database table exists';

    public function handle()
    {
        $tableName = $this->argument('tableName');
        $tableExists = DB::getSchemaBuilder()->hasTable($tableName);
        
        if ($tableExists) {
            $this->info("The table {$tableName} exists!");
        } else {
            $this->error("The table {$tableName} does not exist!");
        }
        
        return 0;
    }
}
