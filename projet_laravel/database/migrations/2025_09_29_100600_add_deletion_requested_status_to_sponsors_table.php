<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend ENUM to include 'deletion_requested'
        // MySQL only; adjust if you change database engine
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sponsors MODIFY status ENUM('pending','validated','rejected','deletion_requested') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Any rows with the extra status must be reset to a valid legacy value before shrinking ENUM
            DB::table('sponsors')->where('status', 'deletion_requested')->update(['status' => 'pending']);
            DB::statement("ALTER TABLE sponsors MODIFY status ENUM('pending','validated','rejected') DEFAULT 'pending'");
        }
    }
};
