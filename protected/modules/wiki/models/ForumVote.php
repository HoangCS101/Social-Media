<?php 

namespace humhub\modules\wiki\models;

use Yii;
use humhub\components\ActiveRecord;

/**
 * Class ForumVote
 *
 * @property int $id
 * @property int $forum_id
 * @property int $total_vote
 * @property string $updated_at
 */
class ForumVote extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%forum_vote}}';
    }

    public function rules()
    {
        return [
            [['forum_id'], 'required'],
            [['forum_id', 'total_vote'], 'integer'],
            [['updated_at'], 'safe'],
        ];
    }
}

?>