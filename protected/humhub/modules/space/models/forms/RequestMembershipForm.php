<?php

namespace humhub\modules\space\models\forms;

use Yii;
use yii\base\Model;

/**
 * @author Luke
 * @package humhub.modules_core.space.forms
 * @since 0.5
 */
class RequestMembershipForm extends Model
{
    public $space_id;
    public $message;
    public $options;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['message', 'required'],
            ['options', 'safe'],
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return [
            'message' => Yii::t('SpaceModule.base', 'Your Message'),
        ];
    }

}
