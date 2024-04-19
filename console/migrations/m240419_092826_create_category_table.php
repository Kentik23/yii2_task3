<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m240419_092826_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string()->notNull(),
        ]);

        Yii::$app->db->createCommand()->batchInsert('category', ['title'], [
            ['Food'],
            ['LifeStyle'],
            ['Photography'],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
