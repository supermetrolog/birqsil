<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m230819_145410_create_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'value' => $this->string()->notNull()
        ]);

        $this->batchInsert(
            'unit',
            ['value'],
            [
                ['ml'],
                ['l'],
                ['mg'],
                ['g'],
                ['kg'],
                ['pc'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit}}');
    }
}
