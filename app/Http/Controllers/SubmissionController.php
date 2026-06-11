<?php

namespace App\Http\Controllers;

use App\Models\Colleague;
use App\Models\FileUpload;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index()
    {
        return view('form.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'datum' => 'nullable|string|max:20',
            'project_name' => 'nullable|string|max:255',
            'contactpersoon' => 'nullable|string|max:255',

            'system_accesses' => 'nullable|array',
            'system_accesses.*.system_name' => 'required|string|max:255',
            'system_accesses.*.sort_order' => 'nullable|integer',
            'system_accesses.*.action' => 'nullable|string|max:100',
            'system_accesses.*.test_account' => 'nullable|string|max:500',
            'system_accesses.*.demo_url' => 'nullable|string|max:500',
            'system_accesses.*.access_contact' => 'nullable|string|max:255',
            'system_accesses.*.modules' => 'nullable|string|max:500',
            'system_accesses.*.remarks' => 'nullable|string',

            'colleagues' => 'nullable|array',
            'colleagues.*.name' => 'nullable|string|max:255',
            'colleagues.*.function' => 'nullable|string|max:255',
            'colleagues.*.sort_order' => 'nullable|integer',
            'colleagues.*.wishes' => 'nullable|string',
            'colleagues.*.pain_points' => 'nullable|string',
            'colleagues.*.desired_workflow' => 'nullable|string',
            'colleagues.*.manual_tasks' => 'nullable|array',
            'colleagues.*.manual_tasks.*.description' => 'nullable|string',
            'colleagues.*.manual_tasks.*.frequency' => 'nullable|string|max:100',
            'colleagues.*.manual_tasks.*.time_per_task' => 'nullable|string|max:100',
            'colleagues.*.manual_tasks.*.sort_order' => 'nullable|integer',
            'colleagues.*.missing_features' => 'nullable|array',
            'colleagues.*.missing_features.*.system_name' => 'nullable|string|max:255',
            'colleagues.*.missing_features.*.description' => 'nullable|string',
            'colleagues.*.missing_features.*.workaround' => 'nullable|string|max:500',
            'colleagues.*.missing_features.*.extra_time' => 'nullable|string|max:100',
            'colleagues.*.missing_features.*.sort_order' => 'nullable|integer',
            'colleagues.*.file_ids' => 'nullable|array',
            'colleagues.*.file_ids.*' => 'integer|exists:file_uploads,id',

            'candidates_capabilities' => 'nullable|string',
            'candidates_matching' => 'nullable|string',
            'candidates_mobile' => 'nullable|string|max:100',
            'employers_capabilities' => 'nullable|string',
            'employers_requesting' => 'nullable|string',
            'employers_portal' => 'nullable|string|max:100',

            'scope_replace_or_connect' => 'nullable|string',
            'scope_mvp' => 'nullable|string',
            'scope_budget_deadline' => 'nullable|string',
            'scope_decision_maker' => 'nullable|string|max:255',
            'data_current_systems' => 'nullable|string',
            'data_migration' => 'nullable|string',
            'data_api_integrations' => 'nullable|string',
            'compliance_checks' => 'nullable|string',
            'compliance_privacy' => 'nullable|string',
            'compliance_working_well' => 'nullable|string',
            'compliance_numbers_success' => 'nullable|string',

            'overall_workflows' => 'nullable|string',
            'overall_remarks' => 'nullable|string',
            'overall_file_ids' => 'nullable|array',
            'overall_file_ids.*' => 'integer|exists:file_uploads,id',
        ]);

        $submission = DB::transaction(function () use ($data) {
            // Create submission
            $submission = Submission::create([
                'datum' => $data['datum'] ?? null,
                'project_name' => $data['project_name'] ?? null,
                'contactpersoon' => $data['contactpersoon'] ?? null,
                'candidates_capabilities' => $data['candidates_capabilities'] ?? null,
                'candidates_matching' => $data['candidates_matching'] ?? null,
                'candidates_mobile' => $data['candidates_mobile'] ?? null,
                'employers_capabilities' => $data['employers_capabilities'] ?? null,
                'employers_requesting' => $data['employers_requesting'] ?? null,
                'employers_portal' => $data['employers_portal'] ?? null,
                'scope_replace_or_connect' => $data['scope_replace_or_connect'] ?? null,
                'scope_mvp' => $data['scope_mvp'] ?? null,
                'scope_budget_deadline' => $data['scope_budget_deadline'] ?? null,
                'scope_decision_maker' => $data['scope_decision_maker'] ?? null,
                'data_current_systems' => $data['data_current_systems'] ?? null,
                'data_migration' => $data['data_migration'] ?? null,
                'data_api_integrations' => $data['data_api_integrations'] ?? null,
                'compliance_checks' => $data['compliance_checks'] ?? null,
                'compliance_privacy' => $data['compliance_privacy'] ?? null,
                'compliance_working_well' => $data['compliance_working_well'] ?? null,
                'compliance_numbers_success' => $data['compliance_numbers_success'] ?? null,
                'overall_workflows' => $data['overall_workflows'] ?? null,
                'overall_remarks' => $data['overall_remarks'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // System accesses
            foreach (($data['system_accesses'] ?? []) as $sa) {
                $submission->systemAccesses()->create($sa);
            }

            // Colleagues
            foreach (($data['colleagues'] ?? []) as $colData) {
                $colleague = $submission->colleagues()->create([
                    'sort_order' => $colData['sort_order'] ?? 0,
                    'name' => $colData['name'] ?? null,
                    'function' => $colData['function'] ?? null,
                    'wishes' => $colData['wishes'] ?? null,
                    'pain_points' => $colData['pain_points'] ?? null,
                    'desired_workflow' => $colData['desired_workflow'] ?? null,
                ]);

                // Manual tasks
                foreach (($colData['manual_tasks'] ?? []) as $task) {
                    $colleague->manualTasks()->create($task);
                }

                // Missing features
                foreach (($colData['missing_features'] ?? []) as $feature) {
                    $colleague->missingFeatures()->create($feature);
                }

                // Associate file uploads
                if (!empty($colData['file_ids'])) {
                    FileUpload::whereIn('id', $colData['file_ids'])
                        ->whereNull('uploadable_id')
                        ->update([
                            'uploadable_type' => Colleague::class,
                            'uploadable_id' => $colleague->id,
                        ]);
                }
            }

            // Overall file uploads
            if (!empty($data['overall_file_ids'])) {
                FileUpload::whereIn('id', $data['overall_file_ids'])
                    ->whereNull('uploadable_id')
                    ->update([
                        'uploadable_type' => Submission::class,
                        'uploadable_id' => $submission->id,
                    ]);
            }

            return $submission;
        });

        return response()->json([
            'success' => true,
            'id' => $submission->id,
            'message' => 'Uitvraag succesvol opgeslagen.',
        ]);
    }
}
