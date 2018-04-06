<?php
$userRepository = require __DIR__ . "/userRepository.php";

return [
    'id' => 'optimize-image',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@webroot' => '@app/public'
    ],
    'modules' => [
        'v1' => [
            'class' => \app\api\modules\v1\Module::class,
        ],
    ],
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => [
                        'error',
                        'warning'
                    ],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'Q4mQW4nZjh6U0NBLDRy4nhepRYVnTntJD9FHt8Cs',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ]
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => [
                        'v1/compress' => 'v1/compress',
                        'v1/info' => 'v1/info',
                        'v1/user'
                    ]
                ],
            ],
        ],
        'user' => [
            'identityClass' => \app\api\common\models\User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'userRepository' => $userRepository
    ],
];
