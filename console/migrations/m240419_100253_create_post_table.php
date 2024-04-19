<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m240419_100253_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string()->notNull(),
            'text' => $this->string()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'status' => $this->integer()->notNull(),
            'image' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-post-category_id',
            'post',
            'category_id'
        );

        $this->addForeignKey(
            'fk-post-category_id',
            'post',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-post-category_id',
            'post'
        );

        $this->dropIndex(
            'idx-post-category_id',
            'post'
        );

        $this->dropTable('post');
    }
}
