# Configuration

## Setup Config Application

Update to your `config`

```php
'components' => [
    ...
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
    ],
    ...
],
...
'modules' => [
    ...
    'setup-rbac' => [
        'class' => 'diecoding\rbac\Module',
    ],
    ...
],
```

Then Execute yii migration here: `yii migrate --migrationPath=@yii/rbac/migrations`

To use menu manager (optional). Execute yii migration here: `yii migrate --migrationPath=@diecoding/rbac/migrations`

See [Yii RBAC](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac) for more detail. You can then access Auth manager through the following URL:

```
http://localhost/path/to/index.php?r=setup-rbac
http://localhost/path/to/index.php?r=setup-rbac/user
http://localhost/path/to/index.php?r=setup-rbac/assignment
http://localhost/path/to/index.php?r=setup-rbac/role
http://localhost/path/to/index.php?r=setup-rbac/permission
http://localhost/path/to/index.php?r=setup-rbac/route
http://localhost/path/to/index.php?r=setup-rbac/rule
http://localhost/path/to/index.php?r=setup-rbac/menu
```

## Access Control Filter

Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control.
As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior.
ACF will check a set of access rules to make sure the current user can access the requested action.

The code below shows how to use ACF which is implemented as `diecoding\rbac\AccessControl`:

> **Note:** Please put it in the application config, not the common config.

```php
...
'as access' => [
    'class' => 'diecoding\rbac\components\AccessControl',
    'allowActions' => [
        // '*',
        'site/*',
        'setup-rbac/*',
        'some-controller/some-action',
        // The actions listed here will be allowed to everyone including guests.
        // So, 'setup-rbac/*' should not appear here in the production, of course.
        // But in the earlier stages of your development, you may probably want to
        // add a lot of actions here until you finally completed setting up rbac,
        // otherwise you may not even take a first step.
    ],
    'denyActions' => [
        'some-controller/some-action',
        // The actions listed here will be deny to everyone including guests.
    ],
],
...
```

## Filter ActionColumn Buttons

When you use `GridView`, you can also filtering button visibility.

```php
use mdm\admin\components\Helper;

'columns' => [
    ...
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => Helper::filterActionColumn('{view}{delete}{posting}'),
    ]
]
```

It will check authorization access of button and show or hide it.

To check access for route, you can use

```php
use mdm\admin\components\Helper;

if(Helper::checkRoute('delete')){
    echo Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
        'class' => 'btn btn-danger',
        'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
        'data-method' => 'post',
    ]);
}

```
