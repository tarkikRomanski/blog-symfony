<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * Uploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = md5(uniqid()) . '.' . $fileExtension;
        $fileType = $this->getFileTypeByExtension($fileExtension);
        $file->move($this->getTargetDirectory(), $fileName);
        return [
            'name' => $fileName,
            'type' => $fileType
        ];
    }

    public function getFileTypeByExtension($extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'png':
            case 'gif':
                return 1;
            default:
                return 0;
        }
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}