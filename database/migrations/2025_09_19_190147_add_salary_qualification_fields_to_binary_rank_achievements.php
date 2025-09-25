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
        Schema::table('binary_rank_achievements', function (Blueprint $table) {
            // Salary qualification tracking fields
            $table->timestamp('salary_qualification_start_date')->nullable()->after('achieved_at');
            $table->boolean('salary_eligible')->default(false)->after('salary_qualification_start_date');
            $table->timestamp('salary_eligible_date')->nullable()->after('salary_eligible');
            $table->integer('qualification_days_remaining')->default(0)->after('salary_eligible_date');
            $table->json('qualification_monthly_tracking')->nullable()->after('qualification_days_remaining');
            $table->boolean('qualification_period_active')->default(false)->after('qualification_monthly_tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('binary_rank_achievements', function (Blueprint $table) {
            $table->dropColumn([
                'salary_qualification_start_date',
                'salary_eligible',
                'salary_eligible_date', 
                'qualification_days_remaining',
                'qualification_monthly_tracking',
                'qualification_period_active'
            ]);
        });
    }
};
