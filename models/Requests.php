<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property int $id
 * @property string $uri URI
 * @property int $timestmp Метка времени
 * @property int $status Статус
 */
class Requests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uri', 'timestmp', 'status'], 'required'],
            [['timestmp', 'status'], 'integer'],
            [['uri'], 'string', 'max' => 500],
            [['uri'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uri' => 'Uri',
            'timestmp' => 'Timestmp',
            'status' => 'Status',
        ];
    }
}
