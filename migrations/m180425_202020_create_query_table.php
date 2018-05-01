<?php

use yii\db\Migration;

/**
 * Handles the creation of table `query`.
 */
class m180425_202020_create_query_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('query', [
            'id' => $this->primaryKey(),
            'start' => $this->date(),
            'end' => $this->date(),
            'inseason' => $this->boolean(),
            'vars' => $this->string(255),
            'stns' => $this->text(),
            'done' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('query');
    }
}
