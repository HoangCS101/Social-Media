<?php

/* @var $this View */
/* @var $options array */
/* @var $title string */
/* @var $subTitle string */
/* @var $classPrefix string */
/* @var $canEdit bool */
/* @var $coverCropUrl string */
/* @var $imageCropUrl string */
/* @var $coverDeleteUrl string */
/* @var $imageDeleteUrl string */
/* @var $imageUploadUrl string */
/* @var $coverUploadUrl string */
/* @var $headerControlView string */
/* @var $coverUploadName string */
/* @var $imageUploadName string */

/* @var $container ContentContainerActiveRecord */

/**
 * Note: Inline styles have been retained for legacy theme compatibility (prior to v1.4)
 */

use humhub\modules\content\assets\ContainerHeaderAsset;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\file\widgets\Upload;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\ProfileLeftNavigation;
use humhub\modules\user\widgets\ProfileRightNavigation;
use humhub\widgets\Button;
use yii\helpers\Html;

ContainerHeaderAsset::register($this);

// if the default banner image is displaying change padding to the lower image height
$bannerProgressBarPadding = $container->getProfileBannerImage()->hasImage() ? '90px 350px' : '50px 350px';
$bannerUpload = Upload::withName($coverUploadName, ['url' => $coverUploadUrl]);

$profileImageUpload = Upload::withName($imageUploadName, ['url' => $imageUploadUrl]);

$profileImageWidth = $container->getProfileImage()->width();
$profileImageHeight = $container->getProfileImage()->height();
?>

<?= Html::beginTag('div', $options) ?>

    <div class="top-header">
        <div class="image-upload-container profile-banner-image-container top-header-thumb">
            <?= $container->getProfileBannerImage()->render('width:100%; height: 420px', ['class' => 'img-profile-header-background']) ?>

            <?php if ($canEdit) : ?>
                <div class="image-upload-loader">
                    <?= $bannerUpload->progress() ?>
                </div>
            <?php endif; ?>

            <?php if ($canEdit) : ?>
                <?= $this->render('containerProfileImageMenu', [
                    'upload' => $bannerUpload,
                    'hasImage' => $container->getProfileBannerImage()->hasImage(),
                    'cropUrl' => $coverCropUrl,
                    'deleteUrl' => $coverDeleteUrl,
                    'dropZone' => '.profile-banner-image-container',
                    'confirmBody' => Yii::t('SpaceModule.base', 'Do you really want to delete your title image?'),
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="profile-section">
            <!-- <div class="row">
                <div class="col col-lg-5 ml-auto col-md-5 col-sm-12 col-12">
                    <ul class="profile-menu">
                        <li>
                            <a href="07-ProfilePage-Photos.html">Photos</a>
                        </li>
                        <li>
                            <a href="09-ProfilePage-Videos.html">Videos</a>
                        </li>
                        <li>
                            <div class="more">
                                <svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
                                <ul class="more-dropdown more-with-triangle">
                                    <li>
                                        <a href="#">Report Profile</a>
                                    </li>
                                    <li>
                                        <a href="#">Block Profile</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!-- <?= $this->render($headerControlView, [
                'container' => $container,
            ]) ?> -->
            <div class="row">
            <div class="col col-lg-5 col-md-5 col-sm-12 col-12">
                <?= ProfileLeftNavigation::widget(['user' => $container]); ?>
            </div>
            <div class="col col-lg-5 ml-auto col-md-5 col-sm-12 col-12">
                <?= ProfileRightNavigation::widget(['user' => $container]); ?>
            </div>
            </div>
            

            <!-- <div class="control-block-button">
                <a href="35-YourAccount-FriendsRequests.html" class="btn btn-control bg-blue">
                    <svg class="olymp-happy-face-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
                </a>

                <a href="#" class="btn btn-control bg-purple">
                    <svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
                </a>

                <div class="btn btn-control bg-primary more">
                    <svg class="olymp-settings-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-settings-icon"></use></svg>

                    <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                        <li>
                            
                            <?php if ($canEdit) : ?>
                                <div class="image-upload-loader" style="padding:<?= $bannerProgressBarPadding ?>">
                                    <?= $bannerUpload->progress() ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($canEdit) : ?>
                                <?= $this->render('containerProfileImageMenu', [
                                    'upload' => $bannerUpload,
                                    'hasImage' => $container->getProfileBannerImage()->hasImage(),
                                    'cropUrl' => $coverCropUrl,
                                    'deleteUrl' => $coverDeleteUrl,
                                    'dropZone' => '.profile-banner-image-container',
                                    'confirmBody' => Yii::t('SpaceModule.base', 'Do you really want to delete your title image?'),
                                ]) ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#update-header-photo">Update Header Photo</a>
                        </li>
                        <li>
                            <a href="29-YourAccount-AccountSettings.html">Account Settings</a>
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
        <div class="top-header-author">
            <div class="author-thumb image-upload-container profile-user-photo-container">
                <?php if ($container->getProfileImage()->hasImage()) : ?>
                    <a data-ui-gallery="spaceHeader" href="<?= $container->profileImage->getUrl('_org') ?>">
                        <?= $container->getProfileImage()->render($profileImageWidth - 28, ['class' => 'img-profile-header-background profile-user-photo', 'link' => false, 'showSelfOnlineStatus' => true]) ?>
                    </a>
                <?php else : ?>
                    <?= $container->getProfileImage()->render($profileImageHeight - 28, ['class' => 'img img-profile-header-background profile-user-photo']) ?>
                <?php endif; ?>
                <?php if($canEdit):?>
                    <?= $this->render('containerProfileImageMenu', [
                        'upload' => $profileImageUpload,
                        'hasImage' => $container->getProfileImage()->hasImage(),
                        'deleteUrl' => $imageDeleteUrl,
                        'cropUrl' => $imageCropUrl,
                        'dropZone' => '.profile-user-photo-container',
                        'confirmBody' => Yii::t('SpaceModule.base', 'Do you really want to delete your profile image?'),
                    ]) ?>
                <?php endif; ?>
            </div>
            <div class="author-content">
                <?= Button::asLink($title)->link($container->getUrl())->cssClass('h4 author-name') ?>
                <div class="<?= $classPrefix ?> country"><?= $subTitle ?></div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
