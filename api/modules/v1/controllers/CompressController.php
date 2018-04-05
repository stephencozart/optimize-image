<?php

namespace app\api\modules\v1\controllers;


use Spatie\ImageOptimizer\OptimizerChainFactory;
use yii\base\Event;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class CompressController extends Controller
{
    public function actionCreate()
    {
        $body = json_decode(\Yii::$app->request->getRawBody(), true);

        $fileContents = file_get_contents($body['url']);

        $info = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $info->buffer($fileContents);

        if (strstr($mime, 'image') === false) {

            throw new BadRequestHttpException('File or url is not an image');

        }

        $temPath = \Yii::getAlias('@app/runtime/temp/');

        $tempFileName = sprintf('%s.%s', uniqid(), md5($fileContents));

        $tempFilePath = $temPath . $tempFileName;

        $this->on(Controller::EVENT_AFTER_ACTION, function() use ($tempFilePath) {
            unlink($tempFilePath);
        });

        file_put_contents($tempFilePath, $fileContents);

        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($tempFilePath);

        return [
            'image' => base64_encode(file_get_contents($tempFilePath))
        ];

    }
}