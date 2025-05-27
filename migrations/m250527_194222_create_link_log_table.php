<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link_log}}`.
 */
class m250527_194222_create_link_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('link_log', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Внешний ключ на таблицу links
        $this->addForeignKey(
            'fk-link_log-link_id',
            'link_log',
            'link_id',
            'links',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-link_log-link_id', 'link_log');
        $this->dropTable('link_log');
    }
}
