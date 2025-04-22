<?php

namespace humhub\modules\mail\helpers;

use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;

class Url extends \yii\helpers\Url
{
    public const ROUTE_SEARCH_TAG = '/mail/tag/search';

    public static function toCreateConversation($userGuid = null)
    {
        $route = $userGuid ? ['/mail/mail/create', 'userGuid' => $userGuid] : ['/mail/mail/create'];
        return static::to($route);
    }

    public static function toDeleteMessageEntry(MessageEntry|SecureMessageEntry $entry)
    {
        if($entry instanceof SecureMessageEntry) {

            return static::to(['/mail/mail/delete-entry', 'id' => $entry->id, 'type' => 'secure']);
        }
        else {
            return static::to(['/mail/mail/delete-entry', 'id' => $entry->id, 'type' => 'normal']);
        }
    }

    public static function toHandleDeleteMessageEntry(MessageEntry|SecureMessageEntry $entry)
    {
        if($entry instanceof SecureMessageEntry) {

            return static::to(['/mail/mail/handle-delete', 'id' => $entry->id, 'type' => 'secure']);
        }
        else {
            return static::to(['/mail/mail/handle-delete', 'id' => $entry->id, 'type' => 'normal']);
        }
    }

    public static function toLoadMessage(string $type)
    {
        return static::to(['/mail/mail/show', 'type' => $type]);
    }

    public static function toUpdateMessage()
    {
        return static::to(['/mail/mail/update']);
    }

    public static function toEditMessageEntry(MessageEntry|SecureMessageEntry $entry)
    {
        if($entry instanceof SecureMessageEntry) {

            return static::to(['/mail/mail/edit-entry', 'id' => $entry->id, 'type' => 'secure']);
        }
        else {
            return static::to(['/mail/mail/edit-entry', 'id' => $entry->id, 'type' => 'normal']);
        }
    }

    public static function toEditConversationTags(Message $message)
    {
        return static::to(['/mail/tag/edit-conversation', 'messageId' => $message->id]);
    }

    public static function toManageTags()
    {
        return static::to(['/mail/tag/manage']);
    }

    public static function toAddTag()
    {
        return static::to(['/mail/tag/add']);
    }

    public static function toEditTag($id)
    {
        return static::to(['/mail/tag/edit', 'id' => $id]);
    }

    public static function toDeleteTag($id)
    {
        return static::to(['/mail/tag/delete', 'id' => $id]);
    }

    public static function toUpdateInbox()
    {
        return static::to(['/mail/inbox/index']);
    }

    public static function toConversationUserList(Message $message)
    {
        return static::to(['/mail/mail/user-list', 'id' => $message->id]);
    }

    public static function toMarkUnreadConversation(Message $message)
    {
        return static::to(['/mail/mail/mark-unread', 'id' => $message->id]);
    }

    public static function toPinConversation(Message $message)
    {
        return static::to(['/mail/mail/pin', 'id' => $message->id]);
    }

    public static function toUnpinConversation(Message $message)
    {
        return static::to(['/mail/mail/unpin', 'id' => $message->id]);
    }

    public static function toLeaveConversation(Message $message)
    {
        return static::to(['/mail/mail/leave', 'id' => $message->id]);
    }

    public static function toMessenger(Message $message = null, $scheme = false)
    {
        $type = $message?->getType();
        $route = $message ? ['/mail/mail/index', 'id' => $message->id, 'type' => $type] : ['/mail/mail/index', 'type' => $type];
        return static::to($route, $scheme);
    }

    public static function toConfig()
    {
        return static::to(['/mail/config']);
    }

    public static function toMessageCountUpdate()
    {
        return static::to(['/mail/mail/get-new-message-count-json']);
    }

    public static function toNotificationList()
    {
        return static::to(['/mail/mail/notification-list']);
    }

    public static function toNotificationSeen()
    {
        return static::to(['/mail/mail/seen']);
    }

    public static function toSearchNewParticipants(Message $message = null)
    {
        $route = $message ? ['/mail/mail/search-user', 'id' => $message->id] : ['/mail/mail/search-user'];
        return static::to($route);
    }

    public static function toAddParticipant(Message $message)
    {
        return static::to(['/mail/mail/add-user', 'id' => $message->id]);
    }

    public static function toReply(Message $message, string $type)
    {
        return static::to(['/mail/mail/reply', 'id' => $message->id, 'type' => $type]);
    }

    public static function toHandleSave(string $op)
    {
        return static::to(['/mail/mail/handle-save', 'op' => $op]);
    }

    public static function toInboxLoadMore()
    {
        return static::to(['/mail/inbox/load-more']);
    }

    public static function toInboxUpdateEntries()
    {
        return static::to(['/mail/inbox/update-entries']);
    }

    public static function toLoadMoreMessages()
    {
        return static::to(['/mail/mail/load-more']);
    }

    public static function toLoginSecureChat()
    {
        return static::to(['/mail/mail/login']);
    }

    public static function toRegisterSecureChat()
    {
        return static::to(['/mail/mail/register']);
    }

}
