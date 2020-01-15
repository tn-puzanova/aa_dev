<?php


namespace olympic\forms;

use dictionary\helpers\DictSpecialTypeOlimpicHelper;
use olympic\models\SpecialTypeOlimpic;
use yii\base\Model;

class SpecialTypeOlimpicEditForm extends  Model
{
    public $olimpic_id;
    public $special_type_id;
    public $_specialTypeOlimpic;

    public function __construct(SpecialTypeOlimpic $specialTypeOlimpic, $config = [])
    {
        $this->olimpic_id = $specialTypeOlimpic->olimpic_id;
        $this->special_type_id = $specialTypeOlimpic->special_type_id;
        $this->_specialTypeOlimpic = $specialTypeOlimpic;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['olimpic_id', 'special_type_id'], 'required'],
            [['olimpic_id', 'special_type_id'], 'integer'],
            ['special_type_id','unique', 'targetClass' => SpecialTypeOlimpic::class, 'filter' => ['<>', 'id', $this->_specialTypeOlimpic->id], 'targetAttribute' => ['olimpic_id', 'special_type_id'],
                'message' => 'Этот вид уже назначен данной олимпиаде']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return SpecialTypeOlimpic::labels();
    }

    public  function specialTypeOlimpicList(): array
    {
       return DictSpecialTypeOlimpicHelper::specialTypeOlimpicList();
    }
}
