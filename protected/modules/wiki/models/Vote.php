<?php

namespace humhub\modules\wiki\models;

use Yii;
use humhub\components\ActiveRecord;

/**
 * Class Vote
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $forum_id
 * @property boolean $is_vote
 * @property integer $value_vote
 */
class Vote extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%vote}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'forum_id', 'value_vote'], 'required'],
            [['user_id', 'forum_id', 'value_vote'], 'integer'],
            [['is_vote'], 'boolean'],
        ];
    }
}
?>