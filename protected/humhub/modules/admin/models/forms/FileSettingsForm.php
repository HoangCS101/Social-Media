<?php

namespace humhub\modules\admin\models\forms;

use Yii;
use yii\base\Model;

/**
 * FileSettingsForm
 * @property int $maxFileSize
 * @property int $excludeMediaFilesPreview Exclude media files from stream attachment list
 * @property int $useXSendfile
 * @property string $allowedExtensions
 *
 * @since 0.5
 */
class FileSettingsForm extends Model
{
    public $maxFileSize;
    public $excludeMediaFilesPreview;
    public $useXSendfile;
    public $allowedExtensions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $settingsManager = Yii::$app->getModule('file')->settings;

        $this->maxFileSize = $settingsManager->get('maxFileSize') / 1024 / 1024;
        $this->excludeMediaFilesPreview = $settingsManager->get('excludeMediaFilesPreview');
        $this->useXSendfile = $settingsManager->get('useXSendfile');
        $this->allowedExtensions = $settingsManager->get('allowedExtensions');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['allowedExtensions'], 'match', 'pattern' => '/^[A-Za-z0-9_,]+$/u'],
            [['useXSendfile', 'excludeMediaFilesPreview'], 'integer'],
            [['maxFileSize'], 'required'],
            [['maxFileSize'], 'integer', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'maxFileSize' => Yii::t('AdminModule.settings', 'Maximum upload file size (in MB)'),
            'useXSendfile' => Yii::t('AdminModule.settings', 'Use X-Sendfile for File Downloads'),
            'excludeMediaFilesPreview' => Yii::t('AdminModule.settings', 'Exclude media files from stream attachment list'),
            'allowedExtensions' => Yii::t('AdminModule.settings', 'Allowed file extensions'),
        ];
    }


    /**
     * Saves the form
     *
     * @return bool
     */
    public function save()
    {
        $settingsManager = Yii::$app->getModule('file')->settings;
        $settingsManager->set('maxFileSize', (int)$this->maxFileSize * 1024 * 1024);
        $settingsManager->set('excludeMediaFilesPreview', $this->excludeMediaFilesPreview);
        $settingsManager->set('useXSendfile', $this->useXSendfile);
        $settingsManager->set('allowedExtensions', strtolower($this->allowedExtensions));

        return true;
    }

}
