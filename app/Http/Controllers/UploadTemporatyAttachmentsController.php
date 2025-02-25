<?php

namespace App\Http\Controllers;

use App\Traits\hasAttachments;
use Illuminate\Http\Request;

class UploadTemporatyAttachmentsController extends Controller
{
    use hasAttachments;

    function uploadAttachments(Request $request)
    {
        return $this->upload($request, 'temp/attachments');
    }


    function revertAttachments(Request $request)
    {
        return $this->revert($request);
    }

}