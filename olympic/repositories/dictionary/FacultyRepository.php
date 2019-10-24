<?php
<<<<<<< HEAD:backend/repositories/dictionary/FacultyRepository.php

namespace backend\repositories\dictionary;

use backend\models\dictionary\Faculty;
=======
namespace olympic\repositories\dictionary;

use olympic\models\dictionary\Faculty;
use yii\web\NotFoundHttpException;
>>>>>>> #10:olympic/repositories/dictionary/FacultyRepository.php


class FacultyRepository
{
    public function get($id): Faculty
    {
        if (!$model = Faculty::findOne($id)) {
            throw new NotFoundHttpException('Faculty не найдено.');
        }
        return $model;
    }

    public function save(Faculty $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Faculty $model): void
    {
        if (!$model->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }


}