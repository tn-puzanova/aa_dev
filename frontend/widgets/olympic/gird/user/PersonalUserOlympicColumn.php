<?php

namespace frontend\widgets\olympic\gird\user;

use common\auth\helpers\UserSchoolHelper;
use olympic\helpers\DiplomaHelper;
use olympic\models\OlimpicList;
use testing\helpers\TestAttemptHelper;
use testing\helpers\TestHelper;
use common\helpers\EduYearHelper;
use testing\models\TestAttempt;
use yii\grid\DataColumn;
use yii\helpers\Html;


class PersonalUserOlympicColumn extends DataColumn
{
    protected function renderDataCellContent($model, $key, $index): string
    {
        return  $this->text($model->olympicOne);
    }

    private function text(OlimpicList $olympic) {
        if ($olympic->isRegStatus()) {
            return $this->isNoAttemptOrNoEndAttempt($olympic) ? $this->linkTest($olympic) : $this->getAttempt($olympic)->mark;
        } else {
            return $this->isNoAttemptOrNoEndAttempt($olympic) ? $this->linkDiploma($olympic) : $this->getAttempt($olympic)->mark."<br />".$this->linkDiploma($olympic);
        }
    }

    private function linkTest(OlimpicList $olympic) {
        return $this->getTest($olympic) ? Html::a("Начать заочный тур", ['/test-attempt/start',
            'test_id'=> $this->getTest($olympic)],
            ['data' => ['confirm' => 'Вы действительно хотите начать заочный тур ?', 'method' => 'POST'],
                'class' =>'btn btn-primary'] ):"";
    }

    private function linkDiploma(OlimpicList $olympic) {
        return  $this->getDiploma($olympic) ? Html::a(is_null($this->getDiploma($olympic)->reward_status_id) ?"Сертификат":"Диплом", ['diploma/index', 'id'=> $this->getDiploma($olympic)->id]) :"";
    }

    private function getClassUser() {
        return  UserSchoolHelper::userClassId($this->getUser(), EduYearHelper::eduYear());
    }

    private function getUser() {
        return \Yii::$app->user->identity->getId();
    }

    private function getTest(OlimpicList $olympic)
    {
        return TestHelper::testAndClassActiveOlympicList($olympic->id, $this->getClassUser());
    }

    private function getAttempt(OlimpicList $olympic)
    {
        return TestAttemptHelper::Attempt($this->getTest($olympic), $this->getUser());
    }

    private function isNoAttemptOrNoEndAttempt(OlimpicList $olympic) {
        return !$this->getAttempt($olympic) || $this->getAttempt($olympic)->isAttemptNoEnd();
    }

    private function getDiploma(OlimpicList $olympic) {
        return DiplomaHelper::diplomaId($this->getUser(), $olympic->id);
    }

}