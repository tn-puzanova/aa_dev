<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dateDod dod\models\DateDod */
/* @var $model dod\forms\DateDodEditForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div>
    <?php $form = ActiveForm::begin([ 'enableAjaxValidation' => true, 'id' => 'form-date-dod']); ?>
    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'date_time')->widget(\kartik\datetime\DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Введите дату и время ...'],
                'pluginOptions' => [
                    'language' => 'ru',
                    'autoclose' => true,
                    'format' => 'yyyy.mm.dd hh:ii'
                ]
            ]); ?>
            <?= $form->field($model, 'broadcast_link')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::a('Сохранить', $form->action, ['class'=>'btn btn-primary','data-method'=>'POST']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

