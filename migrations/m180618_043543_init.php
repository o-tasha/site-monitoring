<?php

use yii\db\Migration;

/**
 * Class m180618_043543_init
 */
class m180618_043543_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('requests', [
            'id' => $this->primaryKey(),
            'uri' => $this->string(500)->unique()->notNull()->comment('URI'),
            'timestmp' => $this->integer()->notNull()->comment('Метка времени'),
            'status' => $this->smallInteger(1)->notNull()->comment('Статус'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180618_043543_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180618_043543_init cannot be reverted.\n";

        return false;
    }
    */
}
