<?php

namespace diecoding\rbac;

use Yii;

/**
 * @inheritDoc
 * 
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 1.0.0
 */
class Module extends \mdm\admin\Module
{
    const CACHE_TAG = 'diecoding.rbac';

    /**
     * @var string the name of the table storing menu item hierarchy. Defaults to "auth_menu".
     */
    public $menuTable = '{{%auth_menu}}';

    /**
     * @var integer Cache duration. Default to a hour.
     */
    public $cacheDuration = 1 * 60 * 60;

    /**
     * @inheritDoc
     */
    public $controllerNamespace = 'mdm\admin\controllers';

    /**
     * @inheritDoc
     */
    public $layout = 'left-menu';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['diecoding-rbac'])) {
            Yii::$app->i18n->translations['diecoding-rbac'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@diecoding/rbac/languages',
            ];
        }

        $this->controllerMap = [
            'menu' => [
                'class' => 'diecoding\rbac\controllers\MenuController',
            ],
        ];

        $this->setViewPath('@mdm/admin/views');
    }
}
