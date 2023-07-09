<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 */
class m230709_151352_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'source_name' => $this->string()->notNull(),
            'real_name' => $this->string()->notNull(),
            'full_path' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'extension' => $this->string(),
            'size' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}
