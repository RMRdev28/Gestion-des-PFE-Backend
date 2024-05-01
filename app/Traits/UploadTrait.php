<?php




namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{



    public function upload($base64File, $folder)
    {
        $fileData = explode(',', $base64File);
        $fileExtension = $this->getFileExtension($fileData[0]);
        $fileName = $this->generateFileName($fileExtension);
        $filePath = $folder . '/' . $fileName;

        Storage::disk('public')->put($filePath, base64_decode($fileData[1]));

        $fileInfo = [
            'fileName' => $fileName,
            'filePath' => $filePath,
            'fileExtension' => $fileExtension,
        ];

        return $fileInfo;
    }

    private function getFileExtension($fileData)
    {
        $mime = explode(';', $fileData)[0];
        $mimeParts = explode('/', $mime);
        return end($mimeParts);
    }

    private function generateFileName($fileExtension)
    {
        return date('YmdHis') . '_' . uniqid() . '.' . $fileExtension;
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
