<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant}}`.
 */
class m230415_140429_create_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%restaurant}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'legal_name' => $this->string()->null(),
            'address' => $this->string()->null(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
            'deleted_at' => $this->timestamp()->null()
        ]);

        $this->createIndex(
            'idx-restaurant-user_id',
            'restaurant',
            'user_id'
        );

        $this->addForeignKey(
            'fk-restaurant-user_id',
            'restaurant',
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
        $this->dropForeignKey('fk-restaurant-user_id', 'restaurant');
        $this->dropTable('{{%restaurant}}');
    }
}
