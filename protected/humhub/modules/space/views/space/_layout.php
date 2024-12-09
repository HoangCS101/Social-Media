<?php

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\Header;
use humhub\modules\space\widgets\Menu;
use humhub\modules\space\widgets\SpaceContent;
use humhub\modules\ui\view\components\View;
use humhub\widgets\FooterMenu;
use yii\helpers\Url;
use humhub\modules\space\permissions\CreatePrivateSpace;
use humhub\modules\space\permissions\CreatePublicSpace;



/**
 * @var View $this
 * @var Space $space
 * @var string $content
 */

/** @var ContentContainerController $context */
$context = $this->context;
$space = $context->contentContainer;
$manager = Yii::$app->user->permissionmanager;
$canCreateSpace = $manager->can(new CreatePublicSpace()) || $manager->can(new CreatePrivateSpace());

?>
<div class="container space-layout-container">
    <div class="row">
        <div class="col-md-12">
            <?= Header::widget(['space' => $space]); ?>
        </div>
    </div>
    <div class="row space-content">
        <div class="col-md-2 layout-nav-container">

            <?= Menu::widget(['space' => $space]); ?>
            <div class="ui-block rounded-[20px]">
                <div class="ui-block-title flex justify-between">
                    <h6 class="title">Spaces</h6>
                    <?php if ($canCreateSpace) : ?>
                        <li>
                            <div class="dropdown-footer">
                                <a href="#" class="btn btn-info pr-3 pl-3x" data-action-click="ui.modal.load"
                                data-action-url="<?= Url::to(['/space/create/create']) ?>">
                                    <i class="fa fa-plus m-0"></i>
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                </div>
                <ul class="widget w-friend-pages-added notification-list friend-requests">

                    <?php
                    function getRandomElements($array, $count)
                    {
                        // Kiểm tra nếu số phần tử của mảng nhỏ hơn hoặc bằng số lượng cần lấy
                        if (count($array) <= $count) {
                            return $array; // Trả về toàn bộ mảng nếu nhỏ hơn hoặc bằng $count
                        }

                        // Lấy $count phần tử ngẫu nhiên từ mảng
                        $randomKeys = array_rand($array, $count);

                        // Nếu chỉ chọn 1 phần tử, array_rand() trả về 1 khóa, cần chuyển nó thành mảng
                        if (!is_array($randomKeys)) {
                            $randomKeys = [$randomKeys];
                        }

                        // Trả về các phần tử từ mảng gốc theo các khóa ngẫu nhiên
                        return array_intersect_key($array, array_flip($randomKeys));
                    }
                    $spaces = Space::find()->all();
                    $data = getRandomElements($spaces, 5);
                    foreach ($data as $space) { ?>
                        <li class="inline-items">
                            <div class="author-thumb pt-[10px]">
                                <?php echo $space->getProfileImage()->render(30, ['class' => 'space-avatar', 'id' => 'space-account-image']);
                                ?>
                            </div>
                            <div class="notification-event">
                                <a href="index.php?r=space%2Fspace&cguid=<?= $space->guid ?>"
                                    class="h6 notification-friend">
                                    <?php echo $space->name ?> </a>
                                <span class="chat-message-item"> <?php echo $space->description ?> </span>
                            </div>
                            <span class="notification-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="ADD TO YOUR FAVS">
                                <a href="#">
                                    <svg class="olymp-star-icon">
                                        <use xlink:href="svg-icons/sprites/icons.svg#olymp-star-icon"></use>
                                    </svg>
                                </a>
                            </span>

                        </li>
                        <?php
                    }
                    ?>
                    <li class="inline-items">
                        <a href="index.php?r=space%2Fspaces" class="text-center text-[12px] text-center opacity-80">
                            <p class="text-center"> More Spaces</p>
                        </a>

                    </li>

                </ul>
            </div>

        </div>
        <div class="col-md-<?= ($this->hasSidebar()) ? '7' : '10' ?> layout-content-container">
            <?= SpaceContent::widget(['contentContainer' => $space, 'content' => $content]) ?>
        </div>
        <?php if ($this->hasSidebar()): ?>
            <div class="col-md-3 layout-sidebar-container">
                <?= $this->getSidebar() ?>
                <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>
            </div>

        <?php endif; ?>
    </div>

    <?php if (!$this->hasSidebar()): ?>
        <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_FULL_PAGE]); ?>
    <?php endif; ?>
</div>