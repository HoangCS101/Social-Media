<?php

use yii\helpers\Html;

/** @var $originator \humhub\modules\user\models\User */

echo Yii::t('ContentModule.activities', '{displayName} created a new {contentTitle}.', [
    '{displayName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{contentTitle}' => $this->context->getContentInfo($source)
]);
