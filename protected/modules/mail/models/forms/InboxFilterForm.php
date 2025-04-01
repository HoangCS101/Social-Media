<?php

namespace humhub\modules\mail\models\forms;

use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
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
    private $wasLastPage;

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
    public function init($type = 'normal')
    {
        parent::init();

        if($type == 'normal') {
            if ($this->autoLoad === self::AUTO_LOAD_ALL || $this->autoLoad === self::AUTO_LOAD_GET) {
                $keyword = Yii::$app->request->get('keyword');
                if ($keyword !== null) {
                    $this->term = $keyword;
                }
            }
    
            $this->query = UserMessage::findByUser();
        }
        
        if($type == 'secure') {
            $apiUrl = 'https://your-node-server.com/api/conversations';
        
            try {
                $client = new \yii\httpclient\Client();
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl($apiUrl)
                    ->setHeaders([
                        'secret_key' . Yii::$app->user->identity->secret_key, // Thêm token nếu cần
                        'Accept' => 'application/json'
                    ])
                    ->send();

                if ($response->isOk) {
                    $this->query = $response->data; // Gán dữ liệu lấy từ API vào $this->query
                } else {
                    throw new \Exception('Lỗi khi gọi API: ' . $response->content);
                }
            } catch (\Exception $e) {
                Yii::error('Failed to fetch API: ' . $e->getMessage());
                $this->query = []; // Gán danh sách rỗng nếu lỗi
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        if(!empty($this->term)) {
            $messageEntryContentSubQuery = MessageEntry::find()->where('message_entry.message_id = message.id')
                ->andWhere($this->createTermLikeCondition('message_entry.content'));

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

    public function secureApply()
    {
        $apiUrl = 'https://secure-message-service.com/api/conversations';

        $user = Yii::$app->user->identity;
        if (!$user) {
            throw new HttpException('User not authenticated');
        }

        $userKey = UserKey::findOne(['user_id' => $user->id]);
        if (!$userKey || empty($userKey->secret_key)) {
            throw new HttpException('User doesn\'t register to access secure chat');
            
        }

        try {
            $response = Yii::$app->httpClient->get($apiUrl, [], [
                'headers' => [
                    'secret_key' => $userKey,
                    'Accept' => 'application/json',
                ],
            ])->send();

            if ($response->isOk) {
                return $response->data; // Trả về JSON từ API
            } else {
                return [
                    'error' => 'API request failed',
                    'status' => $response->statusCode,
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'Exception occurred',
                'message' => $e->getMessage(),
            ];
        }
    }
    private function createTermLikeCondition($column)
    {
        return new LikeCondition($column, 'LIKE', $this->term);
    }

    /**
     * @return UserMessage[]
     */
    public function getPageNormal()
    {
        $this->apply();
        $module = Module::getModuleInstance();
        $pageSize = $this->from ? $module->inboxUpdatePageSize : $module->inboxInitPageSize;
        $result = $this->query->limit($pageSize)->all();
        $this->wasLastPage = count($result) < $pageSize;
        return $result;
    }

    public function getPageSecure()
    {
        $data = $this->secureApply();
        return $data;
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
