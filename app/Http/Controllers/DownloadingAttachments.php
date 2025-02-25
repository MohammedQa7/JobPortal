<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DownloadingAttachments extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        if (Storage::disk('public')->exists($request->attachmentPath)) {
            $attachments_path = public_path('storage/' . $request->attachmentPath);
            return Response::download($attachments_path);
        } else {
            abort(404, 'No file were found');
        }
    }
}