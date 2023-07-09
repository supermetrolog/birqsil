<?php

use common\enums\Status;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_item}}`.
 */
class m230709_094329_create_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_item}}', [
            'id' => $this->primaryKey(),
            'restaurant_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(Status::Active->value),
            'ordering' => $this->integer()->notNull()->unique(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
            'deleted_at' => $this->timestamp()
        ]);

        $this->createIndex(
            'idx-menu_item-restaurant_id',
            'menu_item',
            'restaurant_id'
        );

        $this->addForeignKey(
            'fk-menu_item-restaurant_id',
            'menu_item',
            'restaurant_id',
            'restaurant',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-menu_item-restaurant_id', 'menu_item');
        $this->dropTable('{{%menu_item}}');
    }
}
