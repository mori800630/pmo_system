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
        Schema::table('checklists', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('order');
            $table->unsignedBigInteger('submitted_by')->nullable()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('submitted_by');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('submitted_at');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_comment')->nullable()->after('reviewed_at');

            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['reviewed_by']);

            $table->dropColumn([
                'status',
                'submitted_by',
                'submitted_at',
                'reviewed_by',
                'reviewed_at',
                'review_comment',
            ]);
        });
    }
};


