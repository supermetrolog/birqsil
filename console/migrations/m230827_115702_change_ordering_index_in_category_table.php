<?php

use yii\db\Migration;

/**
 * Class m230827_115702_change_ordering_index_in_category_table
 */
class m230827_115702_change_ordering_index_in_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('idx-category-restaurant_id-ordering', 'category');
        $this->createIndex(
            'idx-category-ordering',
            'category',
            'ordering'
        );
        $this->createIndex(
            'idx-category-restaurant_id',
            'category',
            'restaurant_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-category-ordering', 'category');
        $this->dropIndex('idx-category-restaurant_id', 'category');
        $this->createIndex(
            'idx-category-restaurant_id-ordering',
            'category',
            ['restaurant_id', 'ordering'],
            true
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230827_115702_change_ordering_index_in_category_table cannot be reverted.\n";

        return false;
    }
    */
}
