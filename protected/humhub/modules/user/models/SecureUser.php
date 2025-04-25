namespace humhub\modules\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_key".
 *
 * @property int $id
 * @property int $user_id
 * @property string $secret_key
 * @property string $created_at
 */

class UserKey extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_key';
    }

    public function rules()
    {
        return [
            [['user_id', 'secret_key'], 'required'],
            [['user_id'], 'integer'],
            [['secret_key'], 'string'],
            [['created_at'], 'safe'],
            [['user_id'], 'unique'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Lưu secret key của user
     */
    public static function saveKey($userId, $secretKey)
    {
        $userKey = self::findOne(['user_id' => $userId]) ?? new self();
        $userKey->user_id = $userId;
        $userKey->secret_key = $secretKey;
        return $userKey->save();
    }
}
