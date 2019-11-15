<?php

namespace diecoding\rbac\models;

use Yii;
use yii\db\Query;
use diecoding\rbac\Module;

/**
 * This is the model class for table "{{%auth_menu}}".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $route
 * @property string $assign
 * @property string $visible
 * @property string $icon
 * @property int $order
 * @property string $options
 *
 * @property Menu $menuParent
 * @property Menu[] $menuChildren 
 *  
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 1.0.0
 */
class Menu extends \yii\db\ActiveRecord
{
    public $parent_name;

    private static $_routes;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $config = new Module([]);

        return $config->menuTable;
    }

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        $config = new Module([]);

        if ($config->db !== null) {
            return Yii::$app->get($config->db);
        }

        return parent::getDb();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [
                ['parent_name'], 'in',
                'range' => static::find()->select(['name'])->column(),
                'message' => 'Menu "{value}" not found.',
            ],
            [['parent', 'route', 'assign', 'visible', 'icon', 'order', 'options'], 'default'],
            [['parent'], 'filterParent', 'when' => function () {
                return !$this->isNewRecord;
            }],
            [['order'], 'integer'],
            [
                ['route'], 'in',
                'range' => static::getSavedRoutes(),
                'message' => 'Route "{value}" not found.',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',
            'route' => 'Route',
            'assign' => 'Assign',
            'visible' => 'Visible',
            'icon' => 'Icon',
            'order' => 'Order',
            'options' => 'Options',
        ];
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parent = $this->parent;
        $db     = static::getDb();
        $query  = (new Query)->select(['parent'])
            ->from(static::tableName())
            ->where('[[id]]=:id');
        while ($parent) {
            if ($this->id == $parent) {
                $this->addError('parent_name', 'Loop detected.');
                return;
            }
            $parent = $query->params([':id' => $parent])->scalar($db);
        }
    }

    /**
     * Get menu parent
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuParent()
    {
        return $this->hasOne(static::className(), ['id' => 'parent']);
    }

    /**
     * Get menu children
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuChildren()
    {
        return $this->hasMany(static::className(), ['parent' => 'id']);
    }

    /**
     * Get saved routes.
     * @return array
     */
    public static function getSavedRoutes()
    {
        if (self::$_routes === null) {
            self::$_routes = [];
            foreach (Yii::$app->authManager->getPermissions() as $name => $value) {
                if ($name[0] === '/' && substr($name, -1) != '*') {
                    self::$_routes[] = $name;
                }
            }
        }
        return self::$_routes;
    }

    public static function getMenuSource()
    {
        $tableName = static::tableName();
        return (new Query())
            ->select(['m.id', 'm.name', 'm.route', 'parent_name' => 'p.name'])
            ->from(['m' => $tableName])
            ->leftJoin(['p' => $tableName], '[[m.parent]]=[[p.id]]')
            ->all(static::getDb());
    }
}
