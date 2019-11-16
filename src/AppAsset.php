<?php

namespace diecoding\rbac;

use yii\web\AssetBundle;

/**
 * Main asset bundle.
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
