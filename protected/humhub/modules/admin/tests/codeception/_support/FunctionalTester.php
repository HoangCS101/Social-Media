<?php

namespace admin;

use Codeception\Lib\Friend;
use humhub\modules\user\models\GroupPermission;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \FunctionalTester
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */
}
