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
</head>

<body>
    <?php $this->beginBody() ?>
    <header class="header z-[1000] p-0" id="site-header">
        <div class="container mx-auto z-10">
            <div class="page-title p-0">
                <?= SiteLogo::widget() ?>
            </div>
            <div class="header-content-wrapper min-h-[70px] ">
                <form class="search-bar w-search notification-list friend-requests ">
                    <div class="form-group with-button">
                        <input class="form-control js-user-search" placeholder="Search here people or pages..."
                            type="text">
                    </div>
                </form>
                <a href="#" class="link-find-friend">Find Friends</a>
                <div class="control-block">
                    <div class="control-icon more has-items z-[9999]">
                        <?= NotificationArea::widget() ?>
                    </div>

                    <div class="author-page author vcard inline-items more z-[9999]">
                        <?= AccountTopMenu::widget() ?>
                    </div>

                </div>
            </div>
            <!-- <div class="w-full bg-purple-dark-opacity h-10"> -->   
        </div>
        <div id="topbar-second" class="topbar bg-[#515365] z-1">
            <div class="container">
                <ul class="nav" id="top-menu-nav">

                    <?= Chooser::widget() ?>

                    <?= TopMenu::widget() ?>
                </ul>

                <ul class="nav pull-right" id="search-menu-nav">
                    <?= TopMenuRightStack::widget() ?>
                </ul>
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