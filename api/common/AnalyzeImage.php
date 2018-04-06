<?php

namespace app\api\common;


class AnalyzeImage
{
    protected $fileContents;

    public function __construct($fileContents)
    {
        $this->fileContents = $fileContents;
    }

    public function getContents()
    {
        return $this->fileContents;
    }

    public function getDimensions()
    {
        $sizeInfo = getimagesizefromstring($this->fileContents);

        return [
            'width' => $sizeInfo[0],
            'height' => $sizeInfo[1],
        ];
    }

    public function getMimeType()
    {
        $info = new \finfo(FILEINFO_MIME_TYPE);

        return $info->buffer($this->fileContents);
    }

    public function getFileSize()
    {
        return strlen($this->fileContents);
    }

    public function isSupported()
    {
        return strstr($this->getMimeType(), 'image') !== false;
    }
}