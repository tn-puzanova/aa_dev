<?php

namespace dod\models;

use common\helpers\DateTimeCpuHelper;
use dod\forms\DateDodCreateForm;
use dod\forms\DateDodEditForm;
use dod\models\queries\DateDodQuery;
use yii\db\ActiveRecord;

class DateDod extends ActiveRecord
{
    private $_dod;

    public function __construct($config = [])
    {
        $this->_dod = new Dod();
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'date_dod';
    }

    public static function create(DateDodCreateForm $form, $dod_id)
    {
        $dateDod = new static();
        $dateDod->date_time = $form->date_time;
        $dateDod->dod_id = $dod_id;
        $dateDod->broadcast_link = $form->broadcast_link;
        return $dateDod;
    }

    public function edit(DateDodEditForm $form, $dod_id)
    {
        $this->date_time = $form->date_time;
        $this->broadcast_link = $form->broadcast_link;
        $this->dod_id = $dod_id;
    }

    public function attributeLabels()
    {
        return [
            'date_time' => 'Дата и время',
            'broadcast_link'=>"Код вставки на трансляцию"
        ];
    }

    public function getDodOne() {
        return $this->_dod->dodRelation($this->dod_id)->one();
    }

    public function getDateStartString(): string
    {
        return  "Дата проведения:". DateTimeCpuHelper::getDateChpu($this->date_time);
    }

    public function getTimeStartString(): string
    {
        return  "Время начала::". DateTimeCpuHelper::getTimeChpu($this->date_time);
    }


    public static function labels(): array
    {
        $dateDod = new static();
        return $dateDod->attributeLabels();
    }

    public static function find(): DateDodQuery
    {
        return new DateDodQuery(static::class);
    }



}