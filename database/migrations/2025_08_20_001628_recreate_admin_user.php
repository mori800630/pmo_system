<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 既存のAdminユーザーを削除
        DB::table('users')->where('username', 'Admin')->delete();
        
        // 新しいAdminユーザーを作成
        User::create([
            'name' => 'Admin',
            'username' => 'Admin',
            'email' => 'admin@pmo-system.com',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // このマイグレーションをロールバックする場合は何もしない
        // ユーザーデータの削除は取り消せないため
    }
};
