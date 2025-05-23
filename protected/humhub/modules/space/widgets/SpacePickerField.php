<?php

namespace humhub\modules\space\widgets;

use humhub\modules\space\models\Space;
use humhub\modules\ui\form\widgets\BasePicker;
use Yii;
use yii\helpers\Html;

/**
 * Mutliselect input field for selecting space guids.
 *
 * @package humhub.modules_core.space.widgets
 * @since 1.2
 * @author buddha
 */
class SpacePickerField extends BasePicker
{
    /**
     * @inheritdoc
     * Min guids string value of Space model equal 2
     */
    public $minInput = 2;

    /**
     * @inheritdoc
     */
    public $defaultRoute = '/space/browse/search-json';
    public $itemClass = Space::class;
    public $itemKey = 'guid';

    /**
     * @inheritdoc
     */
    protected function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            'data-tags' => 'false',
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        $result = parent::getData();
        $allowMultiple = $this->maxSelection !== 1;
        $result['placeholder'] = Yii::t('SpaceModule.chooser', 'Select {n,plural,=1{space} other{spaces}}', ['n' => ($allowMultiple) ? 2 : 1]);
        $result['placeholder-more'] = Yii::t('SpaceModule.chooser', 'Add Space');
        $result['no-result'] = Yii::t('SpaceModule.chooser', 'No spaces found for the given query');

        if ($this->maxSelection) {
            $result['maximum-selected'] = Yii::t('SpaceModule.chooser', 'This field only allows a maximum of {n,plural,=1{# space} other{# spaces}}', ['n' => $this->maxSelection]);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getItemText($item)
    {
        return $item->getDisplayName();
    }

    /**
     * @inheritdoc
     */
    protected function getItemImage($item)
    {
        return Image::widget(['space' => $item, 'width' => 24]);
    }

}
