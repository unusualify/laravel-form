<?php

namespace Unusualify\LaravelForm\Http\Controllers;

use Illuminate\Http\Request;
use Unusualify\LaravelForm\Services\FilePondManager;


class FilePondController extends Controller
{
    public $filePondManager;

    public function __construct(FilePondManager $filePondManager)
    {
        $this->filePondManager = $filePondManager;
    }

    public function upload(Request $request)
    {
        ob_end_clean(); // if I remove this, it does not work

        return response($this->filePondManager->createTemporaryFile($request));
    }

    public function delete(Request $request)
    {
        return $this->filePondManager->deleteTemporaryFile( $request );
    }

    public function preview(Request $request, $folder)
    {
        return $this->filePondManager->previewFile($folder);
    }
}
