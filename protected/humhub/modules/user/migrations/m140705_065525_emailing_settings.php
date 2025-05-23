<?php


use humhub\components\Migration;
use yii\db\Query;

class m140705_065525_emailing_settings extends Migration
{
    public function up()
    {

        $rows = (new Query())
            ->select("*")
            ->from('user')
            ->all();
        foreach ($rows as $row) {

            // Ignore deleted users
            if ($row['status'] == 3) {
                continue;
            }

            $userId = $row['id'];
            $receive_email_notifications = $row['receive_email_notifications'];
            $receive_email_messaging = $row['receive_email_messaging'];
            $receive_email_activities = $row['receive_email_activities'];

            $this->insert('user_setting', [
                'user_id' => $userId,
                'module_id' => 'core',
                'name' => 'receive_email_notifications',
                'value' => $receive_email_notifications,
            ]);

            $this->insert('user_setting', [
                'user_id' => $userId,
                'module_id' => 'core',
                'name' => 'receive_email_messaging',
                'value' => $receive_email_messaging,
            ]);

            $this->insert('user_setting', [
                'user_id' => $userId,
                'module_id' => 'core',
                'name' => 'receive_email_activities',
                'value' => $receive_email_activities,
            ]);
        }

        $this->dropColumn('user', 'receive_email_notifications');
        $this->dropColumn('user', 'receive_email_messaging');
        $this->dropColumn('user', 'receive_email_activities');

        if (!$this->isInitialInstallation()) {
            $this->insert('setting', [
                'name' => 'receive_email_activities',
                'value' => '1',
                'name' => 'mailing',
            ]);
            $this->insert('setting', [
                'name' => 'receive_email_notifications',
                'value' => '2',
                'name' => 'mailing',
            ]);
        }
    }

    public function down()
    {
        echo "m140705_065525_emailing_settings does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
