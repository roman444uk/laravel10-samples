<?php

namespace App\Http\Controllers\System;

use App\Classes\Helpers\Db\FileHelper;
use App\Classes\Helpers\Db\StageHelper;
use App\Data\FileData;
use App\Enums\NewsFileTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileUploadRequest;
use App\Http\Requests\System\FileDestroyRequest;
use App\Http\Requests\System\FileStoreRequest;
use App\Models\File;
use App\Models\Stage;
use App\Services\Db\System\FileService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileController extends Controller
{
    public function __construct(
        protected FileService $fileService
    )
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileStoreRequest $request)
    {
        $operationResponse = $this->fileService->storeUploadedFile(
            $request->file('file'),
            FileHelper::getModelByOwnerAliasAndId($request->validated('owner'), $request->validated('owner_id')),
            $request->validated('type'),
            getUserId()
        );

        if (!$operationResponse->isSuccess()) {
            return errorJsonResponse($operationResponse->getMessage());
        }

        $file = $operationResponse->get('file');

        return operationJsonResponse(
            $operationResponse,
            null,
            $this->getExtraData([
                'file' => FileData::fromModel($file)
            ], $file)
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileDestroyRequest $request, File $file = null)
    {
        if ($file) {
            return operationJsonResponse($this->fileService->destroy($file), null, $this->getExtraData([], $file));
        }

        if ($request->has('owner') && $request->has('owner_id') || $request->has('type')) {
            return operationJsonResponse($this->fileService->destroyByOwnerCredentials(
                $request->validated('owner'),
                $request->validated('owner_id'),
                $request->validated('type'),
                $request->validated('index'),
            ));
        }

        throw new NotFoundHttpException();
    }

    protected function getExtraData(array $extraDataDefault, File $file): array
    {
        $extraData = [];

        $owner = FileHelper::getModelOwner($file);

        if ($owner instanceof Stage) {
            $extraData['isReadyForWork'] = StageHelper::isReadyForWork($owner);
        }

        return array_merge($extraDataDefault, $extraData);
    }
}
