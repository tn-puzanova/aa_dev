<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $chairmans dictionary\models\DictChairmans*/
/* @var $model dictionary\forms\DictChairmansEditForm */
/* @var $form yii\widgets\ActiveForm */

?>
<div>
    <?php $form = ActiveForm::begin(['id' => 'form-chairmans', 'enableAjaxValidation' => true, 'options' => ['enctype'=>'multipart/form-data']]); ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'first_name')->textInput() ?>

            <?= $form->field($model, 'patronymic')->textInput() ?>

            <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

            <?= $chairmans->photo ? Html::img($chairmans->getThumbFileUrl('photo', 'admin')) : "Нет подписи"; ?>

            <?= $form->field($model, 'photo')->fileInput(); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
    <?php ActiveForm::end(); ?>
</div>
