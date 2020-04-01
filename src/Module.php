<?php

namespace diecoding\rbac;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @inheritdoc
 * 
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 0.0.1
 */
class Module extends \mdm\admin\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'mdm\admin\controllers';

    /**
     * @inheritdoc
     */
    public $layout = 'left-menu';

    /**
     * @var int|string the bootstrap library version.
     *
     * To use with bootstrap 3 - you can set this to any string starting with 3 (e.g. `3` or `3.3.7` or `3.x`)
     * To use with bootstrap 4 - you can set this to any string starting with 4 (e.g. `4` or `4.1.1` or `4.x`)
     *
     * This property can be set up globally in Yii application params in your Yii2 application config file.
     *
     * For example:
     * `Yii::$app->params['bsVersion'] = '4.x'` to use with Bootstrap 4.x globally
     *
     * If this property is set, this setting will override the `Yii::$app->params['bsVersion']`. If this is not set, and
     * `Yii::$app->params['bsVersion']` is also not set, this will default to `3.x` (Bootstrap 3.x version).
     */
    public $bsVersion;

    /**
     * @var bool flag to detect whether bootstrap 4.x version is set
     */
    protected $_isBs4;

    /**
     * @inheritdoc
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

        if (php_sapi_name() !== 'cli') {
            AppAsset::register(Yii::$app->view);
        }

        $this->controllerMap = [
            'menu' => [
                'class' => 'diecoding\rbac\controllers\MenuController',
            ],
        ];

        $this->setViewPath('@mdm/admin/views');

        if (strpos($_SERVER['REQUEST_URI'], $this->id) && $this->isBs4()) {
            Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [];
            Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [];
        }
    }

    /**
     * Configures the bootstrap version settings
     * @return string the bootstrap lib parsed version number
     */
    protected function configureBsVersion()
    {
        $v = empty($this->bsVersion) ? ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3') : $this->bsVersion;
        $ver = static::parseVer($v);
        $this->_isBs4 = $ver === '4';
        return $ver;
    }

    /**
     * Validate if Bootstrap 4.x version
     * @return bool
     *
     * @throws InvalidConfigException
     */
    public function isBs4()
    {
        if (!isset($this->_isBs4)) {
            $this->configureBsVersion();
        }
        return $this->_isBs4;
    }

    /**
     * Parses and returns the major BS version
     * @param string $ver
     * @return bool|string
     */
    protected static function parseVer($ver)
    {
        $ver = (string) $ver;
        return substr(trim($ver), 0, 1);
    }
}
