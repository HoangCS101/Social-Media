<?php

namespace humhub\modules\mail\models\forms;

use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageType;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\mail\models\UserMessageTag;
use humhub\modules\user\models\UserKey;
use humhub\modules\mail\Module;
use humhub\modules\ui\filter\models\QueryFilter;
use Yii;
use yii\base\InvalidCallException;
use yii\db\conditions\ExistsCondition;
use yii\db\conditions\LikeCondition;
use yii\db\conditions\OrCondition;
use yii\httpclient\Client;
use yii\web\HttpException;

class InboxFilterForm extends QueryFilter
{
    /**
     * @var string
     */
    public $term;
 
    /**
     * @var array
     */
    public $participants;

    /**
     * @var array
     */
    public $tags;
    /**
     * @var string
     */
    public $type;

    /**
     * @inheritDoc
     */
    public $autoLoad = self::AUTO_LOAD_ALL;

    /**
     * @inheritDoc
     */
    public $formName = '';

    /**
     * @var int
     */
    public $from;

    /**
     * @var int
     */
    public $ids;

    /**
     * @var
     */
    private $wasLastPage = false;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['term'], 'trim'],
            [['participants'], 'safe'],
            [['tags'], 'safe'],
            [['from'], 'integer'],
            [['ids'], 'integer'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

            if ($this->autoLoad === self::AUTO_LOAD_ALL || $this->autoLoad === self::AUTO_LOAD_GET) {
                $keyword = Yii::$app->request->get('keyword');
                if ($keyword !== null) {
                    $this->term = $keyword;
                }
            }
    
            $this->query = UserMessage::findByUser();
        
        // if($type == 'secure') {
        //     $apiUrl = 'https://your-node-server.com/api/conversations';
        
        //     try {
        //         $client = new \yii\httpclient\Client();
        //         $response = $client->createRequest()
        //             ->setMethod('GET')
        //             ->setUrl($apiUrl)
        //             ->setHeaders([
        //                 'secret_key' . Yii::$app->user->identity->secret_key, // Thêm token nếu cần
        //                 'Accept' => 'application/json'
        //             ])
        //             ->send();

        //         if ($response->isOk) {
        //             $this->query = $response->data; // Gán dữ liệu lấy từ API vào $this->query
        //         } else {
        //             throw new \Exception('Lỗi khi gọi API: ' . $response->content);
        //         }
        //     } catch (\Exception $e) {
        //         Yii::error('Failed to fetch API: ' . $e->getMessage());
        //         $this->query = []; // Gán danh sách rỗng nếu lỗi
        //     }
        // }

        // [{
        //     message_id: '1',
        //     title: 'Hello',
        //     user_id: ['eb03f4c8-8f12-4d79-842a-eff111ba1c2f'],       //     message: ['']

        // }]
    }

    /**
     * @inheritDoc
     */
    public function apply(string $type = 'normal')
    {

        $participantsExistsSubQuery = MessageType::find()->where('message_type.message_id = message.id')
                    ->andWhere(['message_type.type' =>$type]);
        $this->query->andWhere(new ExistsCondition('EXISTS', $participantsExistsSubQuery));
        
        if(!empty($this->term)) {
            if($type === 'normal') {
                $messageEntryContentSubQuery = MessageEntry::find()->where('message_entry.message_id = message.id')
                ->andWhere($this->createTermLikeCondition('message_entry.content'));
            }
            else {
                $messageEntryContentSubQuery = SecureMessageEntry::find()->where('secure_message_entry.message_id = message.id and secure_message_entry.status != "failed"');
            }
            $this->query->andWhere(new OrCondition([
                new ExistsCondition('EXISTS', $messageEntryContentSubQuery),
                $this->createTermLikeCondition('message.title'),
            ]));
        }

        if(!empty($this->participants)) {
            foreach ($this->participants as $userGuid) {
                $participantsExistsSubQuery = UserMessage::find()->joinWith('user')->where('user_message.message_id = message.id')
                    ->andWhere(['user.guid' => $userGuid]);
                $this->query->andWhere(new ExistsCondition('EXISTS', $participantsExistsSubQuery));
            }
        }

        if(!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $participantsExistsSubQuery = UserMessageTag::find()
                    ->where('user_message.message_id = user_message_tag.message_id')
                    ->andWhere('user_message.user_id = user_message_tag.user_id')
                    ->andWhere(['user_message_tag.tag_id' => $tag]);
                $this->query->andWhere(new ExistsCondition('EXISTS', $participantsExistsSubQuery));
            }
        }

        if(!empty($this->from)) {
            $message = Message::findOne(['id' => $this->from]);
            if(!$message) {
                throw new InvalidCallException();
            }
            $this->query->andWhere(['<=', 'message.updated_at', $message->updated_at]);
            $this->query->andWhere(['<>', 'message.id', $message->id]);
        }

        if(!empty($this->ids)) {
            $this->query->andWhere(['IN', 'user_message.message_id', $this->ids]);
        }
    } 

    private function createTermLikeCondition($column)
    {
        return new LikeCondition($column, 'LIKE', $this->term);
    }

    /**
     * @return UserMessage[]
     */
    public function getPage($type)
    {
        $this->apply($type);
        $module = Module::getModuleInstance();
        $pageSize = $this->from ? $module->inboxUpdatePageSize : $module->inboxInitPageSize;
        $result = $this->query->limit($pageSize)->all();
        $this->wasLastPage = count($result) < $pageSize;
        return $result;
    
    }

    public function wasLastPage()
    {
        if($this->wasLastPage === null) {
            throw new InvalidCallException();
        }

        return (int) $this->wasLastPage;
    }

    public function formName()
    {
        return '';
    }

    public function isFiltered(): bool
    {
        return !empty($this->term) || !empty($this->participants) || !empty($this->tags);
    }
}
