<?php

/* @var $searchModel testing\forms\question\search\QuestionSearch */

$this->title = "с развернутым ответом";
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('@backend/views/testing/question/_questions-type-link', ['olympic' => $olympic_id]) ?>
<?= $this->render('@backend/views/testing/question/_girdview', ['olympic_id' => $olympic_id, 'dataProvider' => $dataProvider,
    'searchModel' => $searchModel]) ?>
