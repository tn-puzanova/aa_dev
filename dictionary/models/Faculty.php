<?php

namespace dictionary\models;

use dictionary\forms\FacultyCreateForm;
use dictionary\forms\FacultyEditForm;

class Faculty extends \yii\db\ActiveRecord
{
    //@TODO исправить название класса на DictFaculty

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dict_faculty';
    }

    public static function create(FacultyCreateForm $form): self
    {
        $faculty = new static();
        $faculty->full_name = $form->full_name;
        return $faculty;
    }

    public function edit(FacultyEditForm $form): void
    {
        $this->full_name = $form->full_name;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'full_name' => 'Полное название',
        ];
    }

    public static function labels(): array
    {
        $faculty = new static();
        return $faculty->attributeLabels();
    }
}