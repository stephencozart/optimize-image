<?php

namespace app\api\common\controllers;


use Spatie\ImageOptimizer\OptimizerChainFactory;
use app\api\common\AnalyzeImage;
use yii\rest\Controller;

abstract class BaseImageController extends Controller
{
    protected function analyzeImage()
    {
        $body = $this->getRequestBody();

        $fileContents = file_get_contents($body['url']);

        $image = new AnalyzeImage($fileContents);

        if ($image->isSupported() === false) {

            throw new BadRequestHttpException('File or url is not an image');

        }

        return $image;
    }

    protected function getRequestBody()
    {
        return json_decode(\Yii::$app->request->getRawBody(), true);
    }

    protected function compressImage($tempFilePath, $fileContents)
    {
        file_put_contents($tempFilePath, $fileContents);

        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($tempFilePath);

        $compressedFileContents = file_get_contents($tempFilePath);

        unlink($tempFilePath);

        return new AnalyzeImage($compressedFileContents);
    }
}