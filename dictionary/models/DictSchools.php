<?php


namespace dictionary\models;


use dictionary\forms\DictSchoolsCreateForm;
use dictionary\forms\DictSchoolsEditForm;
use dictionary\forms\DictSpecialityCreateForm;
use dictionary\helpers\DictCountryHelper;
use dictionary\models\queries\DictSchoolsQuery;

class DictSchools extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dict_schools';
    }

    public static function create($name, $country_id, $region_id)
    {
        $schools = new static();
        $schools->name = $name;
        $schools->country_id = $country_id;
        $schools->region_id =  $country_id == DictCountryHelper::RUSSIA ? $region_id : null;
        return $schools;
    }

    public function edit($name, $country_id, $region_id)
    {
        $this->name = $name;
        $this->country_id = $country_id;
        $this->region_id =  $country_id == DictCountryHelper::RUSSIA ? $region_id : null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название учебной организации',
            'country_id' => 'Страна, где расположена учебная организация',
            'region_id' => 'Регион, где расположена учебная организация',
        ];
    }

    public static function labels(): array
    {
        $schools = new static();
        return $schools->attributeLabels();
    }

    public static function find(): DictSchoolsQuery
    {
        return new DictSchoolsQuery(static::class);
    }


}