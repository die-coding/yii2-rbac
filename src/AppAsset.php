<?php

namespace diecoding\rbac;

use yii\web\AssetBundle;

/**
 * @inheritdoc
 * 
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 0.1.0
 */
class AppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@diecoding/rbac/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/style.min.css',
    ];
}
