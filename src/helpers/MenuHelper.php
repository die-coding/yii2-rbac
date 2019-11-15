<?php

namespace diecoding\rbac\helpers;

use Yii;
use yii\helpers\Json;
use diecoding\rbac\models\AppMenu;

/**
 * MenuHelper used to generate menu depend of user role.
 * Usage
 *
 * ```
 * use diecoding\rbac\helpers\MenuHelper;
 * use yii\bootstrap\Nav;
 *
 * echo Nav::widget([
 *    'items' => MenuHelper::getList()
 * ]);
 * ```
 *
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 1.0.0
 */
class MenuHelper
{

    /**
     * Use to get assigned menu of user.
     *
     * @return array
     */
    public static function getList()
    {
        $menus = AppMenu::find()
            ->where(['app_id' => Yii::$app->id, 'visible' => 1])
            ->asArray()
            ->indexBy('id')
            ->all();

        return static::normalizeMenu($menus);
    }

    /**
     * Parse route
     *
     * @param  string $route
     * @return mixed
     */
    public static function parseRoute($route)
    {
        if (StringHelper::startsWith((string) $route, 'http') || StringHelper::startsWith((string) $route, 'www')) {

            return $route;
        }

        if (!empty($route)) {

            return [$route];
        }

        return '#';
    }

    /**
     * Parse route
     *
     * @param  string $assign
     * @param  string $module
     * @return mixed
     */
    public static function parseVisible($assign, $module)
    {
        $activeModule = ($module === AppMenu::MODULE_ALL || $module === Yii::$app->controller->module->id);

        if ($assign === AppMenu::ASSIGN_ALL || $assign === Yii::$app->assign->assign) {
            if ($activeModule) {

                return true;
            }
        } elseif ($assign === AppMenu::ASSIGN_GUEST) {
            if ($activeModule) {

                return Yii::$app->user->isGuest;
            }
        } elseif ($assign === AppMenu::ASSIGN_LOGIN) {
            if ($activeModule) {

                return !Yii::$app->user->isGuest;
            }
        }

        return false;
    }

    /**
     * Normalize menu
     *
     * @param  array   $menus
     * @param  integer $parent
     * @return array
     */
    private static function normalizeMenu(&$menus, $parent = null)
    {
        $result = [];
        $order  = [];
        foreach ($menus as $id => $menu) {
            $visible = static::parseVisible($menu['assign'], $menu['module']);
            if ($menu['parent'] == $parent && $visible) {
                $menu['children'] = static::normalizeMenu($menus, $id);

                $options = Json::decode($menu['options']);
                $item = [
                    'label'   => $menu['name'],
                    'icon'    => $menu['icon'],
                    'url'     => static::parseRoute($menu['route']),
                    'options' => $options ?: [],
                ];

                if ($menu['children'] != []) {
                    $item['items'] = $menu['children'];
                }

                $result[] = $item;
                $order[]  = $menu['order'];
            }
        }

        if ($result != []) {
            array_multisort($order, $result);
        }

        return $result;
    }
}
