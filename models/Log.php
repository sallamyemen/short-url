<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Link;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return 'link_log';
    }

    public function rules()
    {
        return [
            [['link_id', 'ip_address'], 'required'],
            [['link_id'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time(); // сохраняется как int (UNIX timestamp)
            }
            return true;
        }
        return false;
    }
}
