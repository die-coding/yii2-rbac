<?php

namespace diecoding\rbac\helpers;

use Yii;
use yii\caching\TagDependency;
use mdm\admin\components\Configs;
use diecoding\rbac\models\Menu;
use diecoding\rbac\Module;

/**
 * MenuHelper used to generate menu depend of user role.
 * Usage
 *
 * ```
 * use diecoding\rbac\helpers\MenuHelper;
 * use yii\bootstrap\Nav;
 *
 * echo Nav::widget([
 *    'items' => MenuHelper::getList(Yii::$app->user->id)
 * ]);
 * ```
 * 
 * To reformat returned, provide callback to method.
 * 
 * ```
 * $callback = function ($menu) {
 *    $data = \diecoding\rbac\models\Menu::eval($menu['data']);
 *    return [
 *            'label' => $menu['name'],
 *            'url' => [$menu['route']],
 *            'visible' => $menu['visible'],
 *            'options' => $menu['options'],
 *            'items' => $menu['children'],
 *        ]
 *    ]
 * }
 *
 * $items = MenuHelper::getList(Yii::$app->user->id, null, $callback);
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
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * callback should have format like
     * 
     * ```
     * function ($menu) {
     *    $data = \diecoding\rbac\models\Menu::eval($menu['data']);
     *    return [
     *            'label' => $menu['name'],
     *            'url' => [$menu['route']],
     *            'visible' => $menu['visible'],
     *            'options' => $menu['options'],
     *            'items' => $menu['children']
     *        ]
     *    ]
     * }
     * ```
     * @param boolean  $refresh
     * @return array
     */
    public static function getList($userId, $root = null, $callback = null, $refresh = false)
    {
        $config = Configs::instance();
        $module = new Module([]);

        /* @var $manager \yii\rbac\BaseManager */
        $manager = Configs::authManager();
        $menus = Menu::find()->asArray()->indexBy('id')->all();
        $key = [__METHOD__, $userId, $manager->defaultRoles];
        $cache = $config->cache;

        if ($refresh || $cache === null || ($assigned = $cache->get($key)) === false) {
            $routes = $filter1 = $filter2 = [];
            if ($userId !== null) {
                foreach ($manager->getPermissionsByUser($userId) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            foreach ($manager->defaultRoles as $role) {
                foreach ($manager->getPermissionsByRole($role) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            $routes = array_unique($routes);
            sort($routes);
            $prefix = '\\';
            foreach ($routes as $route) {
                if (strpos($route, $prefix) !== 0) {
                    if (substr($route, -1) === '/') {
                        $prefix = $route;
                        $filter1[] = $route . '%';
                    } else {
                        $filter2[] = $route;
                    }
                }
            }
            $assigned = [];
            $query = Menu::find()->select(['id'])->asArray();
            if (count($filter2)) {
                $assigned = $query->where(['route' => $filter2])->column();
            }
            if (count($filter1)) {
                $query->where('route like :filter');
                foreach ($filter1 as $filter) {
                    $assigned = array_merge($assigned, $query->params([':filter' => $filter])->column());
                }
            }
            $assigned = static::requiredParent($assigned, $menus);
            if ($cache !== null) {
                $cache->set($key, $assigned, $module->cacheDuration, new TagDependency([
                    'tags' => Configs::CACHE_TAG
                ]));
            }
        }

        $key = [__METHOD__, $assigned, $root];
        if ($refresh || $callback !== null || $cache === null || (($result = $cache->get($key)) === false)) {
            $result = static::normalizeMenu($assigned, $menus, $callback, $root);
            if ($cache !== null && $callback === null) {
                $cache->set($key, $result, $module->cacheDuration, new TagDependency([
                    'tags' => Configs::CACHE_TAG
                ]));
            }
        }

        return $result;
    }

    /**
     * Ensure all item menu has parent.
     * @param  array $assigned
     * @param  array $menus
     * @return array
     */
    private static function requiredParent($assigned, &$menus)
    {
        $l = count($assigned);
        for ($i = 0; $i < $l; $i++) {
            $id = $assigned[$i];
            $parent_id = $menus[$id]['parent'];
            if ($parent_id !== null && !in_array($parent_id, $assigned)) {
                $assigned[$l++] = $parent_id;
            }
        }

        return $assigned;
    }

    /**
     * Parse route
     * @param  string $route
     * @return mixed
     */
    public static function parseRoute($route)
    {
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }

            return $url;
        }

        return '#';
    }

    /**
     * Parse options
     * @param  string $options
     * @return mixed
     */
    public static function parseOptions($options)
    {
        if (!empty($options)) {
            return Menu::eval($options);
        }

        return [];
    }

    /**
     * Convert visible value to bool
     * @param  string $visible
     * @return mixed
     */
    public static function convertVisible($visible)
    {
        if (!empty($visible)) {
            return Menu::eval($visible);
        }

        return true;
    }

    /**
     * Normalize menu
     * @param  array $assigned
     * @param  array $menus
     * @param  Closure $callback
     * @param  integer $parent
     * @return array
     */
    private static function normalizeMenu(&$assigned, &$menus, $callback, $parent = null)
    {
        $result = [];
        $order = [];
        foreach ($assigned as $id) {
            $menu = $menus[$id];
            if ($menu['parent'] == $parent) {
                $menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
                if ($callback !== null) {
                    $item = call_user_func($callback, $menu);
                } else {
                    $item = [
                        'label' => $menu['name'],
                        'url' => static::parseRoute($menu['route']),
                        'icon' => $menu['icon'],
                        'visible' => static::convertVisible($menu['visible']),
                        'options' => static::parseOptions($menu['options']),
                    ];
                    if ($menu['children'] != []) {
                        $item['items'] = $menu['children'];
                    }
                }
                $result[] = $item;
                $order[] = $menu['order'];
            }
        }
        if ($result != []) {
            array_multisort($order, $result);
        }

        return $result;
    }
}
