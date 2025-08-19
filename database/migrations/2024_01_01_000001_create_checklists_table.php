<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // プロジェクトID
            $table->enum('phase', ['planning', 'execution', 'completion']); // フェーズ（計画・実行・終結）
            $table->string('title'); // チェックリスト項目のタイトル
            $table->text('description')->nullable(); // 詳細説明
            $table->boolean('is_completed')->default(false); // 完了フラグ
            $table->integer('order')->default(0); // 表示順序
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
