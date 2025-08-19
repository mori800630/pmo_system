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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // プロジェクト名
            $table->string('code')->unique(); // プロジェクトコード
            $table->string('pm_name'); // PM名
            $table->text('description')->nullable(); // プロジェクト説明
            $table->enum('status', ['planning', 'execution', 'completion'])->default('planning'); // プロジェクトステータス
            $table->date('start_date')->nullable(); // 開始日
            $table->date('end_date')->nullable(); // 終了日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
