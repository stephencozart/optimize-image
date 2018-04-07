<?php

namespace app\api\modules\v1\controllers;


use app\api\common\AnalyzeImage;
use app\api\common\controllers\BaseImageController;
use Intervention\Image\Constraint as ImageConstraint;
use Intervention\Image\ImageManagerStatic as Image;
use yii\web\BadRequestHttpException;

class ResizeController extends BaseImageController
{
    public function actionCreate()
    {
        return $this->compressEachSize();
        //$this->compressFirst();
    }

    protected function compressEachSize()
    {
        $body = $this->getRequestBody();

        $sizes = $body['sizes'];

        foreach($sizes as $size) {

            if (isset($size['width']) && isset($size['height'])) {

                throw new BadRequestHttpException('You may only specify either width or height, not both');

            }

            if (isset($size['width']) === false && isset($size['height']) === false) {

                throw new BadRequestHttpException('You must specify either a width or height');

            }
        }

        $originalImage = $this->analyzeImage();

        $temPath = \Yii::getAlias('@app/runtime/temp/');

        $tempFileName = sprintf('%s.%s', uniqid('resize.'), md5($originalImage->getContents()));

        $tempFilePath = $temPath . $tempFileName;

        $compressedImage = $this->compressImage($tempFilePath, $originalImage->getContents());

        $noUpSize = function(ImageConstraint $imageConstraint) {

            $imageConstraint->upsize();

        };

        $response = [
            'images' => []
        ];

        foreach($sizes as $size) {

            $sourceImage = Image::make($originalImage->getContents());

            if (isset($size['width'])) {
                $reference = 'widen-' . $size['width'];
                $reSizedImage = $sourceImage->widen($size['width'], $noUpSize);

            } else {
                $reference = 'heighten-' . $size['height'];
                $reSizedImage = $sourceImage->heighten($size['height'], $noUpSize);

            }

            $reSizedImageContents = $reSizedImage->encode(null, 100);

            $temPath = \Yii::getAlias('@app/runtime/temp/');

            $tempFileName = sprintf('%s.%s', uniqid('resize.'.$reference), md5($reSizedImageContents));
            $tempFilePath = $temPath . $tempFileName;
            $compressedReSized = $this->compressImage($tempFilePath, $reSizedImageContents);

            $response['images'][] = [
                'reference' => isset($sizes['reference']) ? $sizes['reference'] : $reference,
                'dimensions' => $compressedReSized->getDimensions(),
                'fileSize' => $compressedReSized->getFileSize(),
                'mime' => $compressedReSized->getMimeType(),
                'image' => base64_encode($compressedReSized->getContents()),
            ];

            $image = null;

            $reSizedImage->destroy();
        }

        $response['images'][] = [
            'reference' => 'original',
            'dimensions' => $compressedImage->getDimensions(),
            'fileSize' => $compressedImage->getFileSize(),
            'mime' => $compressedImage->getMimeType(),
            'image' => base64_encode($compressedImage->getContents()),
        ];

        return $response;
    }

    protected function compressFirst()
    {
        $body = $this->getRequestBody();

        $sizes = $body['sizes'];

        foreach($sizes as $size) {

            if (isset($size['width']) && isset($size['height'])) {

                throw new BadRequestHttpException('You may only specify either width or height, not both');

            }

            if (isset($size['width']) === false && isset($size['height']) === false) {

                throw new BadRequestHttpException('You must specify either a width or height');

            }
        }

        $image = $this->analyzeImage();

        $temPath = \Yii::getAlias('@app/runtime/temp/');

        $tempFileName = sprintf('%s.%s', uniqid('resize.'), md5($image->getContents()));

        $tempFilePath = $temPath . $tempFileName;

        $compressedImage = $this->compressImage($tempFilePath, $image->getContents());

        $noUpSize = function(ImageConstraint $imageConstraint) {

            $imageConstraint->upsize();

        };

        $response = [
            'images' => []
        ];

        foreach($sizes as $size) {

            $sourceImage = Image::make($compressedImage->getContents());

            if (isset($size['width'])) {
                $reference = 'widen-' . $size['width'];
                $resizedImage = $sourceImage->widen($size['width'], $noUpSize);

            } else {
                $reference = 'heighten-' . $size['height'];
                $resizedImage = $sourceImage->heighten($size['height'], $noUpSize);

            }

            $image = new AnalyzeImage($resizedImage->encode(null, 100));

            $response['images'][] = [
                'reference' => isset($sizes['reference']) ? $sizes['reference'] : $reference,
                'dimensions' => $image->getDimensions(),
                'fileSize' => $image->getFileSize(),
                'mime' => $image->getMimeType(),
                'image' => base64_encode($image->getContents()),
            ];

            $image = null;

            $resizedImage->destroy();
        }

        $response['images'][] = [
            'reference' => 'original',
            'dimensions' => $compressedImage->getDimensions(),
            'fileSize' => $compressedImage->getFileSize(),
            'mime' => $compressedImage->getMimeType(),
            'image' => base64_encode($compressedImage->getContents()),
        ];

        return $response;
    }
}