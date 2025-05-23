<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\tests\codeception\unit\components\access;

use humhub\libs\BasePermission;

class AccessTestPermission1 extends BasePermission
{
    public $moduleId = 'content';
    public $id = 'content-test-permission2';
}
