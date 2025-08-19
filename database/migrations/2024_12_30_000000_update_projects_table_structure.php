<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // 既存のカラムを削除
            $table->dropColumn(['code', 'description', 'status', 'start_date', 'end_date']);
            
            // 新しいカラムを追加（nullableで追加してから、デフォルト値を設定）
            $table->enum('health', ['Green', 'Amber', 'Red'])->nullable()->after('pm_name'); // 進捗ヘルス
            $table->string('customer_name')->nullable()->after('health'); // 顧客名
            $table->enum('priority', ['High', 'Medium', 'Low'])->nullable()->after('customer_name'); // 優先度
            $table->enum('phase', ['planning', 'requirements', 'design', 'implementation', 'testing', 'release', 'operation'])->nullable()->after('priority'); // フェーズ
            $table->decimal('budget', 15, 2)->nullable()->after('phase'); // 予算
            $table->date('baseline_start_date')->nullable()->after('budget'); // 計画開始日
            $table->date('baseline_end_date')->nullable()->after('baseline_start_date'); // 計画終了日
            $table->date('actual_start_date')->nullable()->after('baseline_end_date'); // 実績開始日
            $table->date('actual_end_date')->nullable()->after('actual_start_date'); // 実績終了日
            $table->text('deliverables_summary')->nullable()->after('actual_end_date'); // 成果物概要
            $table->json('main_links')->nullable()->after('deliverables_summary'); // 主要リンク（JSON形式で複数保存）
        });

        // 既存データにデフォルト値を設定
        DB::table('projects')->update([
            'health' => 'Green',
            'customer_name' => '未設定',
            'priority' => 'Medium',
            'phase' => 'planning',
        ]);

        // NOT NULL制約を追加
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('health', ['Green', 'Amber', 'Red'])->nullable(false)->change();
            $table->string('customer_name')->nullable(false)->change();
            $table->enum('priority', ['High', 'Medium', 'Low'])->nullable(false)->change();
            $table->enum('phase', ['planning', 'requirements', 'design', 'implementation', 'testing', 'release', 'operation'])->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // 新しいカラムを削除
            $table->dropColumn([
                'health', 'customer_name', 'priority', 'phase', 'budget',
                'baseline_start_date', 'baseline_end_date', 'actual_start_date', 'actual_end_date',
                'deliverables_summary', 'main_links'
            ]);
            
            // 元のカラムを復元
            $table->string('code')->unique()->after('name');
            $table->text('description')->nullable()->after('pm_name');
            $table->enum('status', ['planning', 'execution', 'completion'])->default('planning')->after('description');
            $table->date('start_date')->nullable()->after('status');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }
};
