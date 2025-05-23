<?php

namespace humhub\modules\admin\widgets;

use humhub\components\Widget;
use humhub\modules\admin\models\UserApprovalSearch;

/**
 * Shows pending approvals on dashboard
 *
 * @package humhub.modules_core.admin.widgets
 * @since 0.7
 * @author Luke
 */
class DashboardApproval extends Widget
{
    public function run()
    {
        $users = new UserApprovalSearch();
        if ($users->search()->getCount() !== 0) {
            return $this->render('dashboardApproval', []);
        }
    }

}
