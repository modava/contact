<?php

use yii\db\Migration;

/**
 * Class m201204_040630_alter_ip_address
 */
class m201204_040630_alter_ip_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = Yii::$app->db->getTableSchema('contact')->columns;
        if (is_array($columns) && array_key_exists('ip_address', $columns)) {
            $this->alterColumn('contact', 'ip_address', $this->string(255)->null()->after('content'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201204_040630_alter_ip_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201204_040630_alter_ip_address cannot be reverted.\n";

        return false;
    }
    */
}
