<?php 
namespace humhub\modules\mail\notifications;

use humhub\modules\notification\components\BaseNotification;
use humhub\modules\mmail\models\Request;
use humhub\modules\admin\notifications\AdminNotificationCategory;
use humhub\modules\mail\notifications\MailNotificationCategory;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\notification\models\Notification;
use humhub\modules\user\models\User;
use humhub\modules\user\notifications\Mentioned;
use humhub\modules\notification\components\NotificationCategory;
use humhub\modules\notification\targets\MobileTarget;
use humhub\modules\notification\targets\WebTarget;
use yii\bootstrap\Html;
use Yii;

class NewRequest extends BaseNotification
{
    /**
     * @var Request
     */
    public $source;

    /**
     * @inheritdoc
     */
    public $moduleId = 'request';

    /**
     * @inheritdoc
     */
    public $viewName = 'newRequest';

    public function category()
    {
        return new AdminNotificationCategory();
    }

    /**
     * @inheritdoc
     */
    public function getGroupKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getMailSubject()
    {

        return Yii::t('MailModule.notification', "{displayName} just sent request to register secure chat", [
            'displayName' => $this->originator->displayName,
        ]);
    }

    public function html()
    {
        return Yii::t('MailModule.notification', "<strong>{displayName}</strong> just sent request to register secure chat", [
            'displayName' => $this->originator->displayName,
        ]);
    }


    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return '/index.php?r=admin%2Fsecure%2Fmoderate&id=' . $this->source->id;
    }
}
