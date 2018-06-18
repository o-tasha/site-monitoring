<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;


class MonitorController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->resourceMonitor->checkResources();

        return ExitCode::OK;
    }
}