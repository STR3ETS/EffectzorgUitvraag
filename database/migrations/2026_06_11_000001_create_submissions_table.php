<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Cover
            $table->string('datum', 20)->nullable();
            $table->string('project_name', 255)->nullable();
            $table->string('contactpersoon', 255)->nullable();

            // Section C: Candidates
            $table->text('candidates_capabilities')->nullable();
            $table->text('candidates_matching')->nullable();
            $table->string('candidates_mobile', 100)->nullable();

            // Section C: Employers
            $table->text('employers_capabilities')->nullable();
            $table->text('employers_requesting')->nullable();
            $table->string('employers_portal', 100)->nullable();

            // Section D: Scope & aanpak
            $table->text('scope_replace_or_connect')->nullable();
            $table->text('scope_mvp')->nullable();
            $table->text('scope_budget_deadline')->nullable();
            $table->string('scope_decision_maker', 255)->nullable();

            // Section D: Data & koppelingen
            $table->text('data_current_systems')->nullable();
            $table->text('data_migration')->nullable();
            $table->text('data_api_integrations')->nullable();

            // Section D: Compliance
            $table->text('compliance_checks')->nullable();
            $table->text('compliance_privacy')->nullable();
            $table->text('compliance_working_well')->nullable();
            $table->text('compliance_numbers_success')->nullable();

            // Section E: Overkoepelend
            $table->text('overall_workflows')->nullable();
            $table->text('overall_remarks')->nullable();

            // Status
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
