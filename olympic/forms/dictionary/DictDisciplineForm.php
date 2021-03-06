<?php


namespace olympic\forms\dictionary;

use olympic\models\dictionary\DictDiscipline;

use yii\base\Model;

class DictDisciplineForm extends Model
{
    public $name, $links;

    public function __construct(DictDiscipline $discipline = null, $config = [])
    {
        if ($discipline) {
            $this->name = $discipline->name;
            $this->links = $discipline->links;
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'unique', 'targetClass' => 'backend\models\dictionary\DictDiscipline', 'message' => 'Такая дисциплина уже есть в справочнике'],
            [['name', 'links'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return DictDiscipline::labels();
    }
}