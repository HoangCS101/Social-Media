<?php

namespace humhub\modules\space\widgets;

use humhub\components\Widget;
use humhub\modules\space\models\Space;
use Yii;

/**
 * Used to render a single space chooser result.
 *
 */
class SpaceChooserItem extends Widget
{
    /**
     * @var Space
     */
    public $space;

    /**
     * @var int
     */
    public $updateCount = 0;

    /**
     * @var bool
     */
    public $visible = true;

    /**
     * If true the item will be marked as a following space
     * @var bool
     */
    public $isFollowing = false;

    /**
     * If true the item will be marked as a member space
     * @var string
     */
    public $isMember = false;

    public function run()
    {

        $data = $this->getDataAttribute();
        $badge = $this->getBadge();

        return $this->render('spaceChooserItem', [
            'space' => $this->space,
            'updateCount' => $this->updateCount,
            'visible' => $this->visible,
            'badge' => $badge,
            'data' => $data,
        ]);
    }

    public function getBadge()
    {
        if ($this->isFollowing) {
            return '<i class="fa fa-star badge-space pull-right type tt" title="' . Yii::t('SpaceModule.chooser', 'You are following this space') . '" aria-hidden="true"></i>';
        } elseif ($this->space->isArchived()) {
            return '<i class="fa fa-history badge-space pull-right type tt" title="' . Yii::t('SpaceModule.chooser', 'This space is archived') . '" aria-hidden="true"></i>';
        }
    }

    public function getDataAttribute()
    {
        if ($this->isMember) {
            return 'data-space-member';
        } elseif ($this->isFollowing) {
            return 'data-space-following';
        } elseif ($this->space->isArchived()) {
            return 'data-space-archived';
        } else {
            return 'data-space-none';
        }
    }
}
