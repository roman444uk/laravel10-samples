<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\Db\System\FileService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImagePreviewController extends Controller
{
    public function __construct(
        protected FileService $fileService
    )
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show(Request $request)
    {
        if (!file_exists(public_path($request->get('path')))) {
            throw new NotFoundHttpException();
        }

        return response()->file($this->fileService->getPreview(
            $request->get('path'), $request->get('width'), $request->get('height')
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function showS3(Request $request, File $file)
    {
        $operationResponse = $this->fileService->getPreviewS3(
            $file, implode('x', [$request->get('width'), $request->get('height')])
        );

        return operationJsonResponse($operationResponse, [
            'file', 'url',
        ]);
    }
}

