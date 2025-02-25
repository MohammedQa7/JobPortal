<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


trait hasAttachments
{
    public function upload($request, string $folder_path = null)
    {
        try {
            $request->validate([
                'attachments' => 'required|max:8192', // Validate image
            ]);
            $image = $request->file('attachments');
            $image_path = $image->store($folder_path ?? 'temp/attachments', 'public');
            return $image_path;
        } catch (\Throwable $th) {
            abort(422, 'the image type is not supported');
            throw $th;
        }
    }

    public function revert($request, $temp_attachment_path = null)
    {
        if (is_null($temp_attachment_path)) {
            $attachment = $request['attachment_path'];
            $attachment_path = public_path() . '/storage/' . $attachment;
        } else {
            $attachment_path = $temp_attachment_path;
        }


        if (file_exists($attachment_path)) {
            unlink($attachment_path);
        }
        return '';
    }

    public function moveToNewPath($old_path)
    {
        if (Storage::disk('public')->exists($old_path)) {
            $attachment_filename = basename($old_path); // Filename without the storage path.
            $new_path = 'attachments/' . $attachment_filename;
            Storage::disk('public')->move($old_path, $new_path);
            return $new_path;
        }
    }
}