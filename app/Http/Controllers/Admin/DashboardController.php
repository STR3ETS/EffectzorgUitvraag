<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;

class DashboardController extends Controller
{
    public function index()
    {
        $submissions = Submission::withCount('colleagues')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.dashboard', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        $submission->load([
            'systemAccesses',
            'colleagues.manualTasks',
            'colleagues.missingFeatures',
            'colleagues.fileUploads',
            'fileUploads',
        ]);

        return view('admin.submission-show', compact('submission'));
    }
}
