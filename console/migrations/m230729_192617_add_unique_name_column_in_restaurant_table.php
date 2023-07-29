<?php

use yii\db\Migration;

/**
 * Class m230729_192617_add_unique_name_column_in_restaurant_table
 */
class m230729_192617_add_unique_name_column_in_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'restaurant',
            'unique_name',
            $this->string()
        );

        $models = \common\models\AR\Restaurant::find()->all();

        foreach ($models as $model) {
            $model->unique_name = \common\helpers\RandomHelper::randomString(32);
            $model->save(false);
        }

        $this->alterColumn(
            'restaurant',
            'unique_name',
            $this->string()->notNull()->unique()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'unique_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230729_192617_add_unique_name_column_in_restaurant_table cannot be reverted.\n";

        return false;
    }
    */
}
