<?php


namespace backend\assets\olympic;

use yii\web\AssetBundle;

class OlympicCopyAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets/olympic/dist';
    public $js = [
         'js/copy.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

}