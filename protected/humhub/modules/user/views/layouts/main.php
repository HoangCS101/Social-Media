<?php

use humhub\assets\AppAsset;
use humhub\widgets\FooterMenu;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php $this->head() ?>
    <?= $this->render('@humhub/views/layouts/head'); ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <style>
        .form-group.has-error::after {
            display: none;
        }
        .form-group.has-error input{
            outline: 2px solid red; 
            outline-offset: 2px;
        }
    </style>
</head>

<body class="login-container bg-none bg-white">
<?php $this->beginBody() ?>
<?= $content; ?>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
