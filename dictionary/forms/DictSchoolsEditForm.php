<?php

namespace dictionary\forms;

use dictionary\helpers\DictCountryHelper;
use dictionary\helpers\DictRegionHelper;
use dictionary\models\Country;
use dictionary\models\DictSchools;
use dictionary\models\Region;

class DictSchoolsEditForm extends \yii\base\Model
{
    public $name, $country_id, $region_id, $_school;

    public function __construct(DictSchools $schools, $config = [])
    {
        $this->name = $schools->name;
        $this->country_id = $schools->country_id;
        $this->region_id = $schools->region_id;
        $this->_school = $schools;

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['name'], 'string'],
            ['name', 'unique', 'targetClass' => DictSchools::class, 'filter' => ['<>', 'id', $this->_school->id], 'message' => 'Такая учебная организация уже есть в справочнике'],
            [['country_id', 'region_id'], 'integer'],
            ['region_id', 'required', 'when' => function ($model) {
                return $model->country_id == 46;
            }, 'enableClientValidation' => false],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::class, 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return DictSchools::labels();
    }

    public function regionList(): array
    {
        return DictRegionHelper::regionList();
    }

    public function countryList(): array
    {
        return DictCountryHelper::countryList();
    }


}