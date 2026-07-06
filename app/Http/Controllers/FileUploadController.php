<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        $upload = FileUpload::create([
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'id' => $upload->id,
            'original_name' => $upload->original_name,
            'file_size' => $upload->file_size,
            'mime_type' => $upload->mime_type,
            'url' => \Illuminate\Support\Facades\Storage::disk('public')->url($upload->file_path),
        ]);
    }

    public function destroy(FileUpload $fileUpload): JsonResponse
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if ($disk->exists($fileUpload->file_path)) {
            $disk->delete($fileUpload->file_path);
        }

        $fileUpload->delete();

        return response()->json(['success' => true]);
    }
}
