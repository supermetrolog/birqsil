<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_access_token}}`.
 */
class m230422_211901_create_user_access_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_access_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->unique(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->tinyInteger()->notNull(),
            'expire' => $this->integer()->notNull()->comment('Token life time in seconds')
        ]);

        $this->createIndex(
            'idx-user_access_token-user_id',
            'user_access_token',
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_access_token-user_id',
            'user_access_token',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_access_token-user_id', 'user_access_token');
        $this->dropTable('{{%user_access_token}}');
    }
}
