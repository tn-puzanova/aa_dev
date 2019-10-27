<?php

/* @var $this yii\web\View */
/* @var $model dictionary\forms\CategoryDocForm */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Категории документов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
