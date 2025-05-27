<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m250527_194027_create_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->string(2048)->notNull(),
            'short_code' => $this->string(10)->notNull()->unique(),
            'click_count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%links}}');
    }
}
