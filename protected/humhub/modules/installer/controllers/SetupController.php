<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\installer\controllers;

use Exception;
use humhub\components\access\ControllerAccess;
use humhub\components\Controller;
use humhub\libs\DynamicConfig;
use humhub\modules\admin\widgets\PrerequisitesList;
use humhub\modules\installer\forms\DatabaseForm;
use humhub\modules\installer\Module;
use humhub\services\MigrationService;
use Yii;

/**
 * SetupController checks prerequisites and is responsible for database connection and schema setup.
 *
 * @property Module $module
 * @since 0.5
 */
class SetupController extends Controller
{
    public const PASSWORD_PLACEHOLDER = 'n0thingToSeeHere!';

    /**
     * @inheritdoc
     */
    public $access = ControllerAccess::class;

    public function actionIndex()
    {
        return $this->redirect(['prerequisites']);
    }

    /**
     * Prequisites action checks application requirement using the SelfTest
     * Libary
     *
     * (Step 2)
     */
    public function actionPrerequisites()
    {
        Yii::$app->cache->flush();

        return $this->render('prerequisites', ['hasError' => PrerequisitesList::hasError()]);
    }

    /**
     * Database action is responsible for all database related stuff.
     * Checking given database settings, writing them into a config file.
     *
     * (Step 3)
     */
    public function actionDatabase()
    {
        $errorMessage = "";

        $config = DynamicConfig::load();

        $model = new DatabaseForm();
        if (isset($config['params']['installer']['db']['installer_hostname'])) {
            $model->hostname = $config['params']['installer']['db']['installer_hostname'];
        }

        if (isset($config['params']['installer']['db']['installer_database'])) {
            $model->database = $config['params']['installer']['db']['installer_database'];
        }

        if (isset($config['components']['db']['username'])) {
            $model->username = $config['components']['db']['username'];
        }

        if (isset($config['components']['db']['password'])) {
            $model->password = self::PASSWORD_PLACEHOLDER;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $connectionString = 'mysql:host=' . $model->hostname;
            if ($model->port !== '') {
                $connectionString .= ';port=' . $model->port;
            }
            if (!$model->create) {
                $connectionString .= ';dbname=' . $model->database;
            }

            $password = $model->password;
            if ($password == self::PASSWORD_PLACEHOLDER) {
                $password = $config['components']['db']['password'];
            }

            // Create Test DB Connection
            $dbConfig = [
                'class' => 'yii\db\Connection',
                'dsn' => $connectionString,
                'username' => $model->username,
                'password' => $password,
                'charset' => 'utf8',
            ];

            try {

                /** @var yii\db\Connection $temporaryConnection */
                $temporaryConnection = Yii::createObject($dbConfig);

                // Check DB Connection
                $temporaryConnection->open();

                if ($model->create) {
                    // Try to create DB
                    if (!$temporaryConnection->createCommand('SHOW DATABASES LIKE "' . $model->database . '"')->execute()) {
                        $temporaryConnection->createCommand('CREATE DATABASE `' . $model->database . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')->execute();
                    }
                    $dbConfig['dsn'] .= ';dbname=' . $model->database;
                }

                // Write Config
                $config['components']['db'] = $dbConfig;
                $config['params']['installer']['db']['installer_hostname'] = $model->hostname;
                $config['params']['installer']['db']['installer_database'] = $model->database;

                DynamicConfig::save($config);

                return $this->redirect(['migrate']);
            } catch (Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }

        // Render Template
        return $this->render('database', ['model' => $model, 'errorMessage' => $errorMessage]);
    }


    public function actionMigrate()
    {
        if (!$this->module->checkDBConnection()) {
            return $this->redirect(['/installer/setup/database', 'dbFailed' => 1]);
        }

        $this->initDatabase();
        return $this->redirect(['cron']);
    }


    /**
     * Crontab
     */
    public function actionCron()
    {
        return $this->render('cron', []);
    }

    /**
     * Pretty URLs
     */
    public function actionPrettyUrls()
    {
        return $this->render('pretty-urls');
    }

    public function actionFinalize()
    {
        if (!$this->module->checkDBConnection()) {
            return $this->redirect(['/installer/setup/database', 'dbFailed' => 1]);
        }

        Yii::$app->cache->flush();

        // Start the migration a second time here to retry any migrations aborted by timeouts.
        // In addition, in SaaS hosting, no setup step is required and only this action is executed directly.
        $this->initDatabase();

        return $this->redirect(['/installer/config']);
    }

    private function initDatabase()
    {
        // Flush Caches
        Yii::$app->cache->flush();

        // Migrate Up Database
        MigrationService::create()->migrateUp();

        DynamicConfig::rewrite();

        Yii::$app->setDatabaseInstalled();
    }
}
