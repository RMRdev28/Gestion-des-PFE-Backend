<?php

namespace App\Traits;

trait UploadTrait
{
    public function upload($file, $type)
    {
        if ($file && $file->isValid()) {
            $originalFileName = $file->getClientOriginalName();

            $cleanFileName = str_replace(' ', '', $originalFileName);
            $newFileName = date('his') . $cleanFileName;

            $file->storeAs($type . '/', $newFileName, 'public');

            $fileInfo = [
                'originalName' => $originalFileName,
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];

            return $fileInfo;
        }

        return null;
    }
    public function deleteFileFromStorage($fileName, $type)
    {
        $storagePath = storage_path('app/public/' . $type . '/' . $fileName);

        if (file_exists($storagePath)) {
            unlink($storagePath);
            return true;
        }

        return false;
    }
}
