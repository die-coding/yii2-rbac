<?php

namespace diecoding\rbac;

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
    /**
     * @var Connection Database connection. Defaults to "db".
     */
    public $db = 'db';

    /**
     * @var string the name of the table storing menu item hierarchy. Defaults to "auth_menu".
     */
    public $menuTable = '{{%auth_menu}}';

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

        $this->controllerMap = [
            'menu' => [
                'class' => 'diecoding\rbac\controllers\MenuController',
            ],
        ];

        $this->setViewPath('@mdm/admin/views');
    }
}
