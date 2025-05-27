<?php


namespace app\models;

use yii\db\ActiveRecord;
use app\models\Log;

class Link extends ActiveRecord
{
    public static function tableName()
    {
        return 'links';
    }

    public function rules()
    {
        return [
            [['original_url', 'short_code'], 'required'],
            ['original_url', 'url', 'defaultScheme' => 'http'],
            ['short_code', 'string', 'max' => 10],
            ['short_code', 'unique'],
        ];
    }

    public function getLogs()
    {
        return $this->hasMany(Log::class, ['link_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }
}
