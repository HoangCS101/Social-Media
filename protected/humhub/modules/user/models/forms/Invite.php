<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\models\forms;

use humhub\modules\admin\permissions\ManageGroups;
use humhub\modules\admin\permissions\ManageUsers;
use humhub\modules\user\models\User;
use humhub\modules\user\Module;
use humhub\modules\user\services\LinkRegistrationService;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Url;
use yii\validators\EmailValidator;

/**
 * Invite Form Model
 *
 * @since 1.1
 */
class Invite extends Model
{
    /**
     * @var string user's username or email address
     */
    public $emails;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emails'], 'required'],
            ['emails', 'checkEmails'],
        ];
    }

    /**
     * Checks a comma separated list of e-mails which should invited.
     * E-Mails needs to be valid and not already registered.
     *
     * @param string $attribute
     * @param array $params
     */
    public function checkEmails($attribute, $params)
    {
        if ($this->$attribute != "") {
            foreach ($this->getEmails() as $email) {
                $validator = new EmailValidator();
                if (!$validator->validate($email)) {
                    $this->addError($attribute, Yii::t('UserModule.invite', '{email} is not valid!', ["{email}" => $email]));
                    continue;
                }

                if (User::findOne(['email' => $email]) != null) {
                    $this->addError($attribute, Yii::t('UserModule.invite', '{email} is already registered!', ["{email}" => $email]));
                    continue;
                }
            }
        }
    }

    /**
     * E-Mails entered in form
     *
     * @return array the emails
     */
    public function getEmails()
    {
        $emails = [];
        foreach (explode(',', $this->emails) as $email) {
            $emails[] = trim($email);
        }

        return $emails;
    }

    /**
     * Checks if external user invitation setting is enabled
     *
     * @return bool
     * @throws InvalidConfigException
     * @throws Throwable
     */
    public function canInviteByEmail()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('user');

        return (!Yii::$app->user->isGuest && $module->settings->get('auth.internalUsersCanInviteByEmail'))
            || Yii::$app->user->isAdmin()
            || Yii::$app->user->can([ManageUsers::class, ManageGroups::class]);
    }

    /**
     * Checks if external user invitation setting is enabled
     *
     * @return bool
     * @throws Throwable
     * @throws InvalidConfigException
     */
    public function canInviteByLink()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('user');

        return (!Yii::$app->user->isGuest && $module->settings->get('auth.internalUsersCanInviteByLink'))
            || Yii::$app->user->isAdmin()
            || Yii::$app->user->can([ManageUsers::class, ManageGroups::class]);
    }

    /**
     * @param bool $forceResetToken
     * @return string
     * @throws Exception
     */
    public function getInviteLink($forceResetToken = false)
    {
        $linkRegistrationService = new LinkRegistrationService();
        $token = $linkRegistrationService->getStoredToken();
        if ($forceResetToken || !$token) {
            $token = $linkRegistrationService->setNewToken();
        }

        return Url::to(['/user/registration/by-link', 'token' => $token], true);
    }
}
