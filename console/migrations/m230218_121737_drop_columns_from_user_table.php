<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%user}}`.
 */
class m230218_121737_drop_columns_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user', 'auth_key');
        $this->dropColumn('user', 'verification_key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
