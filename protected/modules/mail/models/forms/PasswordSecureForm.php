<?php

namespace humhub\modules\mail\models\forms;

use yii\base\Model;

class PasswordSecureForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
        ];
    }
}