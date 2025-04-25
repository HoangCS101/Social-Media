<?php
/**
 * Created by PhpStorm.
 * User: kingb
 * Date: 29.07.2018
 * Time: 09:29
 */

namespace humhub\modules\mail\widgets;

use humhub\libs\Html;
use humhub\modules\mail\helpers\Url;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;
use humhub\widgets\JsWidget;
use yii\base\Security;
use Yii;

class ConversationEntry extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'mail.ConversationEntry';

    /**
     * @var MessageEntry|SecureMessageEntry
     */
    public $entry;

    /**
     * @var MessageEntry|SecureMessageEntry
     */
    public $prevEntry;

    /**
     * @var MessageEntry|SecureMessageEntry
     */
    public $nextEntry;

    public $secure = false;

    public bool $showDateBadge = true;

    public array $userColors = ['#34568B', '#FF6F61', '#6B5B95', '#88B04B', '#92A8D1', '#955251', '#B565A7', '#009B77',
        '#DD4124', '#D65076', '#45B8AC', '#EFC050', '#5B5EA6', '#9B2335', '#55B4B0', '#E15D44', '#BC243C', '#C3447A',
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        // echo "<pre>";

        // $this->entry;
        // echo "</pre>";
        // // exit; 
        if ($this->entry instanceof SecureMessageEntry) {
            $this->secure = true;
        }

        if ($this->entry->type === MessageEntry::type()) {
            return $this->runMessage();
        }

        return $this->runState();
    }

    public function runMessage(): string
    {
        
        
        $showUser = $this->showUser();

        return $this->render('conversationEntry', [
            'secure' => $this->secure,
            'entry' => $this->entry,
            'contentClass' => $this->getContentClass(),
            'showUser' => $showUser,
            'userColor' => $showUser ? $this->getUserColor() : null,
            'showDateBadge' => $this->showDateBadge(),
            'options' => $this->getOptions(),
            'isOwnMessage' => $this->isOwnMessage(),
            'content' => $this->getMessageContent(),
        ]);
    }

    public function getMessageContent() {
        if($this->secure) {
            if($this->entry->getDecryptedContent()) return $this->entry->getDecryptedContent();
            if($this->entry->content) {
                return $this->entry->decrypt($this->entry->content, $this->entry->key);
            }
            return 'ERROR';
        }
        else {
            // $content = $message->content;
            // // Loại bỏ đoạn ảnh markdown (giống ![...](...))
            // $cleanContent = preg_replace('/!\[.*?\]\(.*?\)/', '', $content);
            // $cleanContent = trim($cleanContent);
            return $this->entry->content;
        }
    }
    public function runState(): string
    {
        return $this->render('conversationState', [
            'entry' => $this->entry,
            'showDateBadge' => $this->showDateBadge(),
        ]);
    }

    private function getContentClass(): string
    {
        $result = 'conversation-entry-content';

        if ($this->isOwnMessage()) {
            $result .= ' own';
        }

        return $result;
    }

    private function isOwnMessage(): bool
    {
        return $this->entry->user->is(Yii::$app->user->getIdentity());
    }

    public function getData()
    {
        return [
            'entry-id' => $this->entry->id,
            'delete-url' => Url::toDeleteMessageEntry($this->entry),
            'handle-delete-url' => Url::toHandleDeleteMessageEntry($this->entry)
        ];
    }

    public function getAttributes()
    {
        $result = [
            'class' => 'media mail-conversation-entry pr-4',
        ];

        if ($this->isOwnMessage()) {
            Html::addCssClass($result, 'own justify-end');
        }

        if ($this->isPrevEntryFromSameUser()) {
            Html::addCssClass($result, 'hideUserInfo');
        }

        return $result;
    }

    private function isPrevEntryFromSameUser(): bool
    {
        return $this->prevEntry && $this->prevEntry->created_by === $this->entry->created_by;
    }

    private function showUser(): bool
    {
        return !$this->isOwnMessage();
    }

    private function getUserColor(): string
    {
        return $this->userColors[$this->entry->created_by % count($this->userColors)];
    }

    private function showDateBadge(): bool
    {
        if (!$this->showDateBadge) {
            return false;
        }

        if (!$this->prevEntry) {
            return true;
        }

        $previousEntryDay = Yii::$app->formatter->asDatetime($this->prevEntry->created_at, 'php:Y-m-d');
        $currentEntryDay = Yii::$app->formatter->asDatetime($this->entry->created_at, 'php:Y-m-d');

        return $previousEntryDay !== $currentEntryDay;
    }

}
