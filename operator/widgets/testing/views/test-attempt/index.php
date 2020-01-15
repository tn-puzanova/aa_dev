<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="box box-default">
    <div class="box box-header">
        <h4>Попытки данного теста</h4>
    </div>
    <div class="box-body">
        <?= \operator\widgets\adminlte\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['attribute' => 'user_id',
                  'value' => function ($model) {
                    return \olympic\helpers\auth\ProfileHelper::profileFullName($model->user_id);
                  }],
                'start:datetime',
                'end:datetime',
                'mark',
                ['class' => \yii\grid\ActionColumn::class,
                    'template' => '{view} {delete}',
                    'controller' => 'testing/test-attempt',
                ],
            ],
        ]) ?>
    </div>
</div>

