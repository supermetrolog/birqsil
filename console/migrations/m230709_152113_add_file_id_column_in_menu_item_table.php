<?php

use yii\db\Migration;

/**
 * Class m230709_152113_add_file_id_column_in_menu_item_table
 */
class m230709_152113_add_file_id_column_in_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'menu_item',
            'file_id',
            $this->integer()
        );

        $this->createIndex(
            'idx-menu_item-file_id',
            'menu_item',
            'file_id',
            true
        );

        $this->addForeignKey(
            'fk-menu_item-file_id',
            'menu_item',
            'file_id',
            'file',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-menu_item-file_id', 'menu_item');
        $this->dropColumn('menu_item', 'file_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230709_152113_add_file_id_column_in_menu_item_table cannot be reverted.\n";

        return false;
    }
    */
}
