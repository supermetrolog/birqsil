<?php

use yii\db\Migration;

/**
 * Class m230826_201918_change_ordering_index
 */
class m230826_201918_change_ordering_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('ordering', 'menu_item');
        $this->createIndex(
            'idx-menu_item-ordering',
            'menu_item',
            'ordering'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-menu_item-ordering', 'menu_item');
        $this->createIndex(
            'ordering',
            'menu_item',
            'ordering',
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
        echo "m230826_201918_change_ordering_index cannot be reverted.\n";

        return false;
    }
    */
}
