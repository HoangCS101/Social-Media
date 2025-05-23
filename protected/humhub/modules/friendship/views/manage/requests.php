<?php

use humhub\modules\friendship\widgets\ManageMenu;
use yii\bootstrap\Html;
use humhub\widgets\GridView;

?>
<div class="panel-heading">
    <?php echo Yii::t('FriendshipModule.base', '<strong>Pending</strong> friend requests'); ?>
</div>

<?php echo ManageMenu::widget(['user' => $user]); ?>

<div class="panel-body">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'username',
            'profile.firstname',
            'profile.lastname',
            [
                'header' => Yii::t('base', 'Actions'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('Accept', ['/friendship/request/add', 'userId' => $model->id], ['class' => 'btn btn-success btn-sm', 'data-method' => 'POST']);
                    },
                    'view' => function () {
                        return;
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('Deny', ['/friendship/request/delete', 'userId' => $model->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'POST']);
                    },
                ],
            ]],
    ]);
    ?>

</div>



