<?php

namespace humhub\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class RedisController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->session->setFlash('contactFormSubmitted', 'Session data stored in Redis');
        $a = Yii::$app->session->getFlash('contactFormSubmitted');
        echo $a;
    }
    public function actionCaches()
    {
        $cache = Yii::$app->cache;
        $key   = 'new';
        $data  = $cache->get($key);
        if ($data === false) {
            $key  = 'new';
            $data = 'A newly cache added';
            $cache->set($key, $data);
        }
        echo $data;
    }
}
