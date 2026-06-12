<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'owner', 'siswa') NOT NULL DEFAULT 'siswa'");
        }

        $platformAdminId = DB::table('users')
            ->where('role', 'admin')
            ->orderBy('id')
            ->value('id');

        DB::table('users')
            ->where('role', 'admin')
            ->when($platformAdminId, fn ($query) => $query->where('id', '!=', $platformAdminId))
            ->update(['role' => 'owner']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'owner')
            ->update(['role' => 'admin']);

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'siswa') NOT NULL DEFAULT 'siswa'");
        }
    }
};
