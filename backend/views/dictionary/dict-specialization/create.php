<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model dictionary\forms\DictSpecializationCreateForm*/
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Образовательные программы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <?php $form = ActiveForm::begin(['id' => 'form-specialization']); ?>
    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'speciality_id')->dropDownList($model->specialityNameAndCodeList(), ["prompt" => "Выберите направление подготовки"]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
