<?php

use humhub\components\ActiveRecord;
use humhub\modules\file\converter\PreviewImage;
use humhub\modules\file\widgets\FilePreview;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\ui\view\helpers\ThemeHelper;
use humhub\widgets\JPlayerPlaylistWidget;
use yii\helpers\Html;

/* @var  $previewImage PreviewImage */
/* @var  $files \humhub\modules\file\models\File[] */
/* @var  $object ActiveRecord */
/* @var  $excludeMediaFilesPreview bool */
/* @var  $showPreview bool */

$videoExtensions = ['webm', 'mp4', 'ogv', 'mov'];
$images = [];
$videos = [];
$audios = [];

foreach ($files as $file) {
    if ($previewImage->applyFile($file)) {
        $images[] = $file;
    } elseif (in_array(FileHelper::getExtension($file->file_name), $videoExtensions, true)) {
        $videos[] = $file;
    } elseif (FileHelper::getExtension($file->file_name) === 'mp3') {
        $audios[] = $file;
    }
}

$fullWidthColumnClass = 'col-media col-xs-12 col-sm-12 col-md-12';
$nonFluidColumnClass = count($images) > 1 ? 'col-media col-xs-12 col-sm-6 col-md-6' : 'col-media col-xs-12 col-sm-12 col-md-12';
$fluidColumnClass = count($images) > 1 ? 'col-media col-xs-12 col-sm-6 col-md-6' : 'col-media col-xs-12 col-sm-12 col-md-12';
$galleryColumnClass = ThemeHelper::isFluid() ? $fluidColumnClass : $nonFluidColumnClass;
$posts = count($images);
if (count($images) == 1) {
    $posts = "columns-1";
} else {
    $posts = "md:columns-2 columns-1";
}
?>


<?php if (count($files) > 0): ?>
    <!-- hideOnEdit mandatory since 1.2 -->
    <div class="hideOnEdit w-full"> 
        <!-- Show Images as Thumbnails -->
        <?php if ($showPreview): ?>
            <div class="post-files clearfix w-full border-b border-solid border-gray-300" id="post-files-<?= $object->getUniqueId() ?>">
                <?php if (!empty($audios)): ?>
                    <div class="<?= $fullWidthColumnClass ?>">
                        <?= JPlayerPlaylistWidget::widget(['playlist' => $audios]) ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($videos as $video): ?>
                    <?php if (FileHelper::getExtension($video->file_name) === 'webm'): ?>
                        <div class="<?= $fullWidthColumnClass ?>">
                            <a data-ui-gallery="<?= 'gallery-' . $object->getUniqueId() ?>" href="<?= $video->getUrl(); ?>#.webm"
                                title="<?= Html::encode($video->file_name) ?>">
                                <video src="<?= $video->getUrl() ?>#t=0.001" type="video/webm" controls preload="metadata"
                                    height="130"></video>
                            </a>
                        </div>
                    <?php elseif (FileHelper::getExtension($video->file_name) === 'mp4'): ?>
                        <div class="<?= $fullWidthColumnClass ?>">
                            <a data-ui-gallery="<?= 'gallery-' . $object->getUniqueId() ?>" href="<?= $video->getUrl(); ?>#.mp4"
                                title="<?= Html::encode($video->file_name) ?>">
                                <video src="<?= $video->getUrl() ?>#t=0.001" type="video/mp4" controls preload="metadata"
                                    height="130"></video>
                            </a>
                        </div>
                    <?php elseif (FileHelper::getExtension($video->file_name) === 'ogv'): ?>
                        <div class="<?= $fullWidthColumnClass ?>">
                            <a data-ui-gallery="<?= 'gallery-' . $object->getUniqueId() ?>" href="<?= $video->getUrl(); ?>#.ogv"
                                title="<?= Html::encode($video->file_name) ?>">
                                <video src="<?= $video->getUrl() ?>#t=0.001" type="video/ogg" controls preload="metadata"
                                    height="130"></video>
                            </a>
                        </div>
                    <?php elseif (FileHelper::getExtension($video->file_name) === 'mov'): ?>
                        <div class="<?= $fullWidthColumnClass ?>">
                            <a data-ui-gallery="<?= 'gallery-' . $object->getUniqueId() ?>" href="<?= $video->getUrl(); ?>#.mov"
                                title="<?= Html::encode($video->file_name) ?>">
                                <video src="<?= $video->getUrl() ?>#t=0.001" type="video/quicktime" controls preload="metadata"
                                    height="130"></video>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach ?>
                <div class="<?= $posts ?>">
                    <?php foreach ($images as $image): ?>
                        <?php $previewImage->applyFile($image) ?>
                        <a class="break-inside-avoid" data-ui-gallery="<?= 'gallery-' . $object->getUniqueId(); ?>"
                            href="<?= $image->getUrl() ?>#.jpeg" title="<?= Html::encode($image->file_name) ?>">
                            <?= $previewImage->render() ?>
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endif; ?>

        <!-- Show List of all files -->
        <?= FilePreview::widget([
            'excludeMediaFilesPreview' => $excludeMediaFilesPreview,
            'items' => $files,
            'model' => $object,
        ]) ?>
    </div>
<?php endif; ?>