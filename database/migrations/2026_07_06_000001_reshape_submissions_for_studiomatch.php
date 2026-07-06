<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reshapes the intake system from the Effectzorg-specific relational schema
 * to a flexible JSON-based answer store so any HTML uitvraag can drive it.
 *
 * - Adds a single `answers` JSON column on submissions (holds the whole form).
 * - Drops the Effectzorg-specific text columns and the child tables.
 *   File uploads (polymorphic) are kept and now attach to a Submission.
 */
return new class extends Migration
{
    private array $droppedColumns = [
        'candidates_capabilities',
        'candidates_matching',
        'candidates_mobile',
        'employers_capabilities',
        'employers_requesting',
        'employers_portal',
        'scope_replace_or_connect',
        'scope_mvp',
        'scope_budget_deadline',
        'scope_decision_maker',
        'data_current_systems',
        'data_migration',
        'data_api_integrations',
        'compliance_checks',
        'compliance_privacy',
        'compliance_working_well',
        'compliance_numbers_success',
        'overall_workflows',
        'overall_remarks',
    ];

    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->json('answers')->nullable()->after('contactpersoon');
        });

        Schema::table('submissions', function (Blueprint $table) {
            foreach ($this->droppedColumns as $column) {
                if (Schema::hasColumn('submissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Detach any file uploads still bound to the old child models.
        Schema::dropIfExists('missing_features');
        Schema::dropIfExists('manual_tasks');
        Schema::dropIfExists('colleagues');
        Schema::dropIfExists('system_accesses');
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'answers')) {
                $table->dropColumn('answers');
            }

            $table->text('candidates_capabilities')->nullable();
            $table->text('candidates_matching')->nullable();
            $table->string('candidates_mobile', 100)->nullable();
            $table->text('employers_capabilities')->nullable();
            $table->text('employers_requesting')->nullable();
            $table->string('employers_portal', 100)->nullable();
            $table->text('scope_replace_or_connect')->nullable();
            $table->text('scope_mvp')->nullable();
            $table->text('scope_budget_deadline')->nullable();
            $table->string('scope_decision_maker', 255)->nullable();
            $table->text('data_current_systems')->nullable();
            $table->text('data_migration')->nullable();
            $table->text('data_api_integrations')->nullable();
            $table->text('compliance_checks')->nullable();
            $table->text('compliance_privacy')->nullable();
            $table->text('compliance_working_well')->nullable();
            $table->text('compliance_numbers_success')->nullable();
            $table->text('overall_workflows')->nullable();
            $table->text('overall_remarks')->nullable();
        });

        // Note: the dropped child tables are not recreated on rollback.
    }
};
