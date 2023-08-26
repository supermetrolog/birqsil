<?php

use yii\db\Migration;

/**
 * Class m230819_145843_add_columns_in_menu_item_table
 */
class m230819_145843_add_columns_in_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'menu_item',
            'unit_id',
            $this->integer()
        );

        $this->createIndex(
            'idx-menu_item-unit_id',
            'menu_item',
            'unit_id'
        );

        $this->addForeignKey(
            'fk-menu_item-unit_id',
            'menu_item',
            'unit_id',
            'unit',
            'id'
        );

        $this->addColumn(
            'menu_item',
            'price',
            $this->integer()->notNull()->defaultValue(0)
        );

        $this->addColumn(
            'menu_item',
            'sale_price',
            $this->integer()
        );

        $this->addColumn(
            'menu_item',
            'amount',
            $this->integer()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('menu_item', 'amount');
        $this->dropColumn('menu_item', 'sale_price');
        $this->dropColumn('menu_item', 'price');

        $this->dropForeignKey('fk-menu_item-unit_id', 'menu_item');
        $this->dropColumn('menu_item', 'unit_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230819_145843_add_columns_in_menu_item_table cannot be reverted.\n";

        return false;
    }
    */
}
