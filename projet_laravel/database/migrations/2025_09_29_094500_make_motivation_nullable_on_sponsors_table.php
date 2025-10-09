<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make motivation column nullable without requiring doctrine/dbal by using raw SQL.
        // Works for MySQL. Adjust if you change database driver later.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE sponsors MODIFY motivation TEXT NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            // Replace NULL values with empty string before enforcing NOT NULL again
            DB::table('sponsors')->whereNull('motivation')->update(['motivation' => '']);
            DB::statement('ALTER TABLE sponsors MODIFY motivation TEXT NOT NULL');
        }
    }
};
