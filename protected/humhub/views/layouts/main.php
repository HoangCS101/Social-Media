<?php

use humhub\assets\AppAsset;
use humhub\modules\space\widgets\Chooser;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\widgets\SiteLogo;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title><?= strip_tags($this->pageTitle) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php $this->head() ?>
    <?= $this->render('head') ?>
    <style>
        body {
            background-color: #e9e9e969;
        }

        .dropup .dropdown-toggle::after {
            display: none;
        }

        .dropdown-toggle::after {
            display: none;
        }

        . .more-photos span,
        .post .more-photos span,
        .post .post__author-name {
            font-weight: 200;
            opacity: 0.8;
        }

        .post .author-date a {
            font-size: 14px;
        }

        .post .post__date a {
            font-size: 12px;
            color: black;
        }

        .form-group.has-error::after {
            display: none;
        }
    </style>

</head>

<body>
    <?php $this->beginBody() ?>
    <header class="header z-[1000] p-0" id="site-header">
        <div class="container mx-auto z-10">

            <div class="page-title flex ">
                <img src="https://hcmut.edu.vn/img/nhanDienThuongHieu/01_logobachkhoatoi.png" alt="" class="w-[70px] h-auto">
                <?= SiteLogo::widget() ?>
            </div>
            <div class="header-content-wrapper min-h-[70px] flex justify-between">
                <div class="control-block flex min-h-[70px]">
                    <ul class="nav w-[400px] min-h-[70px]" id="search-menu-nav" style="">
                        <?= TopMenuRightStack::widget() ?>
                    </ul>


                </div>
                <div class="control-block flex">
                    <div class="control-icon more has-items z-[9999]">
                        <?= NotificationArea::widget() ?>
                    </div>

                    <div class="author-page author vcard inline-items more z-[9999]">
                        <?= AccountTopMenu::widget() ?>
                    </div>

                </div>
            </div>

        </div>
    </header>

    <div class="z-[1]">
        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>