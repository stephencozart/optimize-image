<?php

namespace app\api\modules\v1\controllers;


use app\api\common\controllers\BaseImageController;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class CompressController extends BaseImageController
{
    public function actionCreate()
    {
        $image = $this->analyzeImage();

        $temPath = \Yii::getAlias('@app/runtime/temp/');

        $tempFileName = sprintf('%s.%s', uniqid('compress.'), md5($image->getContents()));

        $tempFilePath = $temPath . $tempFileName;

        $compressedImage = $this->compressImage($tempFilePath, $image->getContents());

        return [
            'dimensions' => $image->getDimensions(),
            'fileSize' => $compressedImage->getFileSize(),
            'mime' => $image->getMimeType(),
            'image' => base64_encode($compressedImage->getContents()),
        ];

    }
}