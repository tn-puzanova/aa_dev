<?php

namespace testing\forms;

use olympic\helpers\OlympicListHelper;
use testing\helpers\TestQuestionGroupHelper;
use testing\helpers\TestQuestionHelper;
use testing\models\Test;
use testing\models\TestAndQuestions;
use testing\models\TestQuestion;
use yii\base\Model;

class AddFinalMarkTableForm extends Model
{
    public $arrayMark;
    public function __construct($andQuestions, $config = [])
    {
        if ($andQuestions) {
            $this->arrayMark =  array_map(function ($quest) {
            return new AddFinalMarkForm($quest); }, $andQuestions);
        } else {
            $this->arrayMark = [];
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['mark'], 'integer'],
        ];
    }
}