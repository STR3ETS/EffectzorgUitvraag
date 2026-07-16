<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index()
    {
        return view('form.index', ['submission' => null]);
    }

    public function edit(Submission $submission)
    {
        $submission->load('fileUploads');

        return view('form.index', ['submission' => $submission]);
    }

    public function pdf(Submission $submission)
    {
        $submission->load('fileUploads');

        return view('form.pdf', ['submission' => $submission]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateData($request);

        $submission = DB::transaction(function () use ($data) {
            $submission = Submission::create([
                'datum' => $data['datum'] ?? null,
                'project_name' => $data['project_name'] ?? null,
                'contactpersoon' => $data['contactpersoon'] ?? null,
                'answers' => $data['answers'] ?? [],
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            $this->syncFiles($submission, $data['file_ids'] ?? []);

            return $submission;
        });

        return response()->json([
            'success' => true,
            'id' => $submission->id,
            'message' => 'Uitvraag succesvol opgeslagen.',
        ]);
    }

    public function update(Request $request, Submission $submission): JsonResponse
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($submission, $data) {
            $submission->update([
                'datum' => $data['datum'] ?? null,
                'project_name' => $data['project_name'] ?? null,
                'contactpersoon' => $data['contactpersoon'] ?? null,
                'answers' => $data['answers'] ?? [],
                'submitted_at' => now(),
            ]);

            $this->syncFiles($submission, $data['file_ids'] ?? []);
        });

        return response()->json([
            'success' => true,
            'id' => $submission->id,
            'message' => 'Uitvraag succesvol bijgewerkt.',
        ]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'datum' => 'nullable|string|max:50',
            'project_name' => 'nullable|string|max:255',
            'contactpersoon' => 'nullable|string|max:255',
            'answers' => 'nullable|array',
            'file_ids' => 'nullable|array',
            'file_ids.*' => 'integer|exists:file_uploads,id',
        ]);
    }

    /**
     * Attach the given uploaded files to this submission and release any
     * previously-attached files that are no longer referenced.
     */
    private function syncFiles(Submission $submission, array $fileIds): void
    {
        // Release files previously bound to this submission.
        FileUpload::where('uploadable_type', Submission::class)
            ->where('uploadable_id', $submission->id)
            ->update(['uploadable_type' => null, 'uploadable_id' => null]);

        if (empty($fileIds)) {
            return;
        }

        FileUpload::whereIn('id', $fileIds)->update([
            'uploadable_type' => Submission::class,
            'uploadable_id' => $submission->id,
        ]);
    }
}
