<?php

use mdm\admin\components\Configs;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

/**
 * Migration table of table_auth_menu
 *
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 0.0.1
 */
class m191115_184338_auth_menu extends Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * @return bool
     */
    protected function isMSSQL()
    {
        return $this->db->driverName === 'mssql' || $this->db->driverName === 'sqlsrv' || $this->db->driverName === 'dblib';
    }

    protected function isOracle()
    {
        return $this->db->driverName === 'oci';
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $menuTable = Configs::instance()->menuTable;
        $menuTable = $menuTable == "{{%menu}}" ? "{{%auth_menu}}" : $menuTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($menuTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(),
            'visible' => $this->smallInteger()->defaultValue(1),
            'icon' => $this->string(64),
            'order' => $this->integer(),
            'options' => $this->binary(),
            'data' => $this->binary(),
            "FOREIGN KEY ([[parent]]) REFERENCES {$menuTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $menuTable = Configs::instance()->menuTable;
        $menuTable = $menuTable == "{{%menu}}" ? "{{%auth_menu}}" : $menuTable;

        $this->dropTable($menuTable);
    }
}
