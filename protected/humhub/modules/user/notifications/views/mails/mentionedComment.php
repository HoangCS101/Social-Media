<?php
/* @var $this yii\web\View */
/* @var $viewable humhub\modules\user\notifications\Mentioned */
/* @var $originator \humhub\modules\user\models\User */

/* @var $record Notification */

use humhub\modules\notification\models\Notification;
use humhub\widgets\mails\MailButtonList;

?>
<?php $this->beginContent('@notification/views/layouts/mail.php', $_params_); ?>

<?php $comment = $viewable->source; ?>
<?php $contentRecord = $comment->getCommentedRecord() ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
        <tr>
            <td>
                <?= humhub\widgets\mails\MailCommentEntry::widget([
                    'originator' => $originator,
                    'receiver' => $record->user,
                    'comment' => $comment,
                    'date' => $date,
                    'space' => $space,
                ]);
                ?>
            </td>
        </tr>
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td>
                <?= humhub\widgets\mails\MailHeadline::widget(['level' => 3, 'text' => $contentRecord->getContentName() . ':', 'style' => 'text-transform:capitalize;']) ?>
            </td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid <?= Yii::$app->view->theme->variable('background-color-secondary') ?>;border-radius:4px;">
                <?=
                humhub\widgets\mails\MailContentEntry::widget([
                    'originator' => $contentRecord->owner,
                    'receiver' => $record->user,
                    'content' => $contentRecord,
                    'date' => $date,
                    'space' => $space
                ]);
                ?>
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td>
                <?=
                MailButtonList::widget(['buttons' => [
                    humhub\widgets\mails\MailButton::widget(['url' => $url, 'text' => Yii::t('UserModule.notification', 'View Online')])
                ]]);
                ?>
            </td>
        </tr>
    </table>
<?php
$this->endContent();
