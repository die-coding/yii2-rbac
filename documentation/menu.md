# Using Menu

Menu manager used for build hierarchical menu. This is automatically look of user
role and permission then return menus that he has access.

```php
use diecoding\rbac\helpers\MenuHelper;
use yii\bootstrap\Nav;

if (!Yii::$app->user->isGuest) {
    $userId = Yii::$app->user->id;
    echo Nav::widget([
        'items' => MenuHelper::getList($userId),
    ]);
}
```

Return of `diecoding\rbac\helpers\MenuHelper::getList($userId)` has default format like:

```php
[
    [
        'label' => $menu['name'],
        'url' => [$menu['route']],
        'icon' => $menu['icon'],
        'visible' => $menu['visible'],
        'linkOptions' => $menu['options'],
        'items' => [
            [
                'label' => $menu['name'],
                'url' => [$menu['route']],
                'icon' => $menu['icon'],
                'visible' => $menu['visible'],
                'linkOptions' => $menu['options'],
            ],
            ....
        ]
    ],
    ...
]
```

where `$menu` variable correspond with a record of table `menu`. You can customize return format of `diecoding\rbac\helpers\MenuHelper::getList($userId)` by provide a callback to this method.
The callback must have format `function ($menu) {}`.

For example, you just get output from menu like this:

```php
[
    [
        'label' => $menu['name'],
        'url' => [$menu['route']],
        'items' => [
            [
                'label' => $menu['name'],
                'url' => [$menu['route']],,
            ],
            ....
        ]
    ],
    ...
]
```

and then in Your view:

```php
$callback = function($menu){
    // $data = eval($menu['data']); ['data'] column for more information to this menu
    return [
        'label' => $menu['name'],
        'url' => [$menu['route']],
        'items' => $menu['children']
    ];
}

$items = MenuHelper::getList(Yii::$app->user->id, null, $callback);
```

Default result is get from `cache`. If you want to force regenerate, provide boolean `true` as forth parameter.

You can modify callback function for advanced usage.

> **Note:** You can use the documentation from [https://github.com/mdmsoft/yii2-admin/blob/master/docs/guide/using-menu.md](https://github.com/mdmsoft/yii2-admin/blob/master/docs/guide/using-menu.md) and change the `mdm\admin\components\MenuHelper::getAssignedMenu($userId)` to `diecoding\rbac\helpers\MenuHelper::getList($userId)`
