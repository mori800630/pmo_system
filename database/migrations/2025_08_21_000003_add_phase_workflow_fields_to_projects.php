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
        Schema::table('projects', function (Blueprint $table) {
            // フェーズ単位のワークフローフィールド
            $table->string('planning_status')->default('draft')->after('main_links');
            $table->unsignedBigInteger('planning_submitted_by')->nullable()->after('planning_status');
            $table->timestamp('planning_submitted_at')->nullable()->after('planning_submitted_by');
            $table->unsignedBigInteger('planning_reviewed_by')->nullable()->after('planning_submitted_at');
            $table->timestamp('planning_reviewed_at')->nullable()->after('planning_reviewed_by');
            $table->text('planning_review_comment')->nullable()->after('planning_reviewed_at');

            $table->string('execution_status')->default('draft')->after('planning_review_comment');
            $table->unsignedBigInteger('execution_submitted_by')->nullable()->after('execution_status');
            $table->timestamp('execution_submitted_at')->nullable()->after('execution_submitted_by');
            $table->unsignedBigInteger('execution_reviewed_by')->nullable()->after('execution_submitted_at');
            $table->timestamp('execution_reviewed_at')->nullable()->after('execution_reviewed_at');
            $table->text('execution_review_comment')->nullable()->after('execution_reviewed_at');

            $table->string('completion_status')->default('draft')->after('execution_review_comment');
            $table->unsignedBigInteger('completion_submitted_by')->nullable()->after('completion_status');
            $table->timestamp('completion_submitted_at')->nullable()->after('completion_submitted_by');
            $table->unsignedBigInteger('completion_reviewed_by')->nullable()->after('completion_submitted_at');
            $table->timestamp('completion_reviewed_at')->nullable()->after('completion_reviewed_at');
            $table->text('completion_review_comment')->nullable()->after('completion_reviewed_at');

            // 外部キー制約
            $table->foreign('planning_submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('planning_reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('execution_submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('execution_reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completion_submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completion_reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign([
                'planning_submitted_by', 'planning_reviewed_by',
                'execution_submitted_by', 'execution_reviewed_by',
                'completion_submitted_by', 'completion_reviewed_by'
            ]);

            $table->dropColumn([
                'planning_status', 'planning_submitted_by', 'planning_submitted_at',
                'planning_reviewed_by', 'planning_reviewed_at', 'planning_review_comment',
                'execution_status', 'execution_submitted_by', 'execution_submitted_at',
                'execution_reviewed_by', 'execution_reviewed_at', 'execution_review_comment',
                'completion_status', 'completion_submitted_by', 'completion_submitted_at',
                'completion_reviewed_by', 'completion_reviewed_at', 'completion_review_comment'
            ]);
        });
    }
};
