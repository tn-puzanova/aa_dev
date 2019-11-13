<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $olimpiadsTypeTemplates \dictionary\models\OlimpiadsTypeTemplates */
/* @var $model \dictionary\forms\OlimpiadsTypeTemplatesEditForm*/
/* @var $form yii\widgets\ActiveForm */

?>
<div>
    <?php $form = ActiveForm::begin(['id' => 'form-olympiads-type-templates', 'enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'number_of_tours')->dropDownList($model->numberOfTours()) ?>

    <?= $form->field($model, 'form_of_passage')->dropDownList($model->formOfPassage()) ?>

    <?= $form->field($model, 'edu_level_olimp')->dropDownList($model->levelOlimp()) ?>

    <?= $form->field($model, 'special_type')->dropDownList($model->specialTypeOlimpicList()); ?>

    <?= $form->field($model, 'template_id')->dropDownList($model->templatesList()) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
