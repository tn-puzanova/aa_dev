<?php


namespace olympic\forms\dictionary;


use olympic\helpers\dictionary\DictCompetitiveGroupHelper;
use olympic\helpers\dictionary\DictFacultyHelper;
use olympic\helpers\dictionary\DictSpecialityHelper;
use olympic\helpers\dictionary\DictSpecializationHelper;
use olympic\models\dictionary\DictCompetitiveGroup;
use yii\base\Model;

class DictCompetitiveGroupForm extends Model
{

    public $speciality_id, $specialization_id, $edu_level, $education_form_id, $financing_type_id, $faculty_id,
        $kcp, $special_right_id, $passing_score, $is_new_program, $only_pay_status, $competition_count, $education_duration,
        $link;

    public function __construct(DictCompetitiveGroup $competitiveGroup, $config = [])
    {
        if ($competitiveGroup) {
            $this->speciality_id = $competitiveGroup->speciality_id;
            $this->specialization_id = $competitiveGroup->specialization_id;
            $this->edu_level = $competitiveGroup->edu_level;
            $this->education_form_id = $competitiveGroup->education_form_id;
            $this->financing_type_id = $competitiveGroup->financing_type_id;
            $this->faculty_id = $competitiveGroup->faculty_id;
            $this->kcp = $competitiveGroup->kcp;
            $this->special_right_id = $competitiveGroup->special_right_id;
            $this->passing_score = $competitiveGroup->passing_score;
            $this->is_new_program = $competitiveGroup->is_new_program;
            $this->only_pay_status = $competitiveGroup->only_pay_status;
            $this->competition_count = $competitiveGroup->competition_count;
            $this->education_duration = $competitiveGroup->education_duration;
            $this->link = $competitiveGroup->link;
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['speciality_id', 'specialization_id', 'edu_level', 'education_form_id', 'financing_type_id', 'faculty_id', 'kcp'], 'required'],
            [['speciality_id', 'specialization_id', 'edu_level', 'education_form_id', 'financing_type_id', 'faculty_id', 'kcp', 'special_right_id', 'passing_score', 'is_new_program', 'only_pay_status'], 'integer'],
            [['competition_count'], 'number'],
            [['education_duration'], 'safe'],
            [['link'], 'string', 'max' => 255],
            [['speciality_id', 'specialization_id', 'education_form_id', 'financing_type_id', 'faculty_id', 'special_right_id'],
                'unique', 'targetClass' => DictCompetitiveGroup::class, 'targetAttribute' => ['speciality_id', 'specialization_id', 'education_form_id', 'financing_type_id', 'faculty_id', 'special_right_id'],
                'message' => 'Такое сочетание уже есть'],
            ['special_right_id', 'in', 'range' => DictCompetitiveGroupHelper::specialRight(), 'allowArray' => true],
            ['financing_type_id', 'in', 'range' => DictCompetitiveGroupHelper::financingTypes(), 'allowArray' => true],
            ['edu_level', 'in', 'range' => DictCompetitiveGroupHelper::eduLevels(), 'allowArray' => true],
            ['education_form_id', 'in', 'range' => DictCompetitiveGroupHelper::forms(), 'allowArray' => true],

        ];
    }

    public function attributeLabels(): array
    {
        return DictCompetitiveGroup::labels();
    }

    public function formList(): array
    {
        return DictCompetitiveGroupHelper::getEduForms();
    }

    public function financingTypesList(): array
    {
        return DictCompetitiveGroupHelper::getFinancingTypes();
    }

    public function eduLevelsList(): array
    {
        return DictCompetitiveGroupHelper::getEduLevels();
    }


    public function specialRightList(): array
    {
        return DictCompetitiveGroupHelper::getSpecialRight();
    }

    public function facultyList(): array
    {
        return DictFacultyHelper::facultyList();
    }

    public function specializationList(): array
    {
        return DictSpecializationHelper::specializationList();
    }

    public function specialityNameAndCodeList(): array
    {
        return DictSpecialityHelper::specialityNameAndCodeList();
    }
}