<?php

require __DIR__ . '/../config/constants.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/app.php';

(new yii\web\Application($config))->run();