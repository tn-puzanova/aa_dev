<?php


namespace olympic\models;


use yii\db\ActiveRecord;

class Diploma extends ActiveRecord
{
    public static function create($user_id, $olimpic_id, $reward_status_id)
    {
        $diploma = new static();
        $diploma->user_id = $user_id;
        $diploma->olimpic_id = $olimpic_id;
        $diploma->reward_status_id = $reward_status_id;

        return $diploma;
    }

    public function edit($user_id, $olimpic_id, $reward_status_id)
    {
        $this->user_id = $user_id;
        $this->olimpic_id = $olimpic_id;
        $this->reward_status_id = $reward_status_id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diploma';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'olimpic_id' => 'Олимпиада',
            'reward_status_id' => 'Награда',
        ];
    }

    public static function labels()
    {
        $diploma = new static();
        return $diploma->attributeLabels();
    }

}