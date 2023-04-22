<?php

use yii\db\Migration;

/**
 * Class m230422_181620_add_verification_token_column_in_user_table
 */
class m230422_181620_add_verification_token_column_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'user',
            'verification_token',
            $this->string()->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'verification_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230422_181620_add_verification_token_column_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
