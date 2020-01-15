<?php
namespace api\controllers;

use yii\rest\Controller;



class SiteController extends Controller
{
   
    public function actionIndex()
    {
        return [
            'version' => '1.0.0',
        ];
    }

    public function verbs()
    {
        return [
            'index' => ["GET"],
      ];
   }
}
