<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\ui\filter\widgets;

class CheckboxListFilterInput extends CheckboxFilterInput
{
    /**
     * @inheritdoc
     */
    public $view = 'checkboxInput';

    /**
     * @inheritdoc
     */
    public $type = 'checkbox';

    /**
     * @var string data-action-click handler of the input event
     */
    public $clickAction = 'toggleFilter';

    /**
     * @inheritdoc
     */
    public $multiple = true;

    /**
     * @inheritdoc
     */
    public function prepareOptions()
    {
        parent::prepareOptions();
        $this->options['data-action-click'] = $this->clickAction;
        $this->options['data-filter-value'] = $this->value;
    }

}
