<?php

namespace diecoding\rbac\components;

use yii\base\Module;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ```
 * 'as access' => [
 *     'class' => 'mdm\admin\components\AccessControl',
 *     'allowActions' => ['site/login', 'site/error'],
 *     'denyActions' => ['test/*'],
 * ]
 * ```
 * 
 * @inheritdoc
 * 
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 0.0.1
 */
class AccessControl extends \mdm\admin\components\AccessControl
{
    /**
     * @var array List of action that no access.
     */
    public $denyActions = [];

    /**
     * @inheritdoc
     * 
     * @since 0.2.0
     */
    protected function isActive($action)
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = $this->getUser();
        if ($user->getIsGuest()) {
            $loginUrl = null;
            if (is_array($user->loginUrl) && isset($user->loginUrl[0])) {
                $loginUrl = $user->loginUrl[0];
            } else if (is_string($user->loginUrl)) {
                $loginUrl = $user->loginUrl;
            }
            if (!is_null($loginUrl) && trim($loginUrl, '/') === $uniqueId) {
                return false;
            }
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && strpos($id, $mid . '/') === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->denyActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, "*");
                if ($route === '' || strpos($id, $route) === 0) {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                if ($id === $route) {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            }
        }

        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, "*");
                if ($route === '' || strpos($id, $route) === 0) {
                    return false;
                }
            } else {
                if ($id === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }

        return true;
    }
}
