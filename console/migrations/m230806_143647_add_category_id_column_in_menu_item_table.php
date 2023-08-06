<?php

use yii\db\Migration;

/**
 * Class m230806_143647_add_category_id_column_in_menu_item_table
 */
class m230806_143647_add_category_id_column_in_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'menu_item',
            'category_id',
            $this->integer()->notNull()
        );

        $this->createIndex(
            'idx-menu_item-category_id',
            'menu_item',
            'category_id',
        );

        $this->addForeignKey(
            'fk-menu_item-category_id',
            'menu_item',
            'category_id',
            'category',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-menu_item-category_id', 'menu_item');
        $this->dropColumn('menu_item', 'category_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230806_143647_add_category_id_column_in_menu_item_table cannot be reverted.\n";

        return false;
    }
    */
}
