<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\auth\models\User */
/* @var $olympic int */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm-olympic', 'token' => $user->verification_token,
    'olympic_id' => $olympic]);
?>
<div class="verify-email">
    <p>Здравствуйте!</p>

    <p>Для подтвеждения электронной почты, пожалуйста, пройдите по ссылке ниже:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
