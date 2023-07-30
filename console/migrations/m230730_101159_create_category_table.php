<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m230730_101159_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'restaurant_id' => $this->integer()->notNull(),
            'ordering' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
        ]);

        $this->createIndex(
            'idx-category-restaurant_id-name',
            'category',
            ['restaurant_id', 'name'],
            true
        );

        $this->createIndex(
            'idx-category-restaurant_id-ordering',
            'category',
            ['restaurant_id', 'ordering'],
            true
        );

        $this->addForeignKey(
            'fk-category-restaurant_id',
            'category',
            'restaurant_id',
            'restaurant',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-category-restaurant_id', 'category');
        $this->dropTable('{{%category}}');
    }
}
