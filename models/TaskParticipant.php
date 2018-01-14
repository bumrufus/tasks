<?php

namespace humhub\modules\task\models;

use Yii;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "task_participant".
 *
 * The followings are the available columns in table 'task_participant':
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property string $name
 */
class TaskParticipant extends \humhub\components\ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'task_participant';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['task_id', 'required'],
            [['task_id', 'user_id'], 'integer'],
            ['name', 'string', 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task',
            'user_id' => 'User',
            'name' => Yii::t('TaskModule.taskparticipant', 'Name'),
        ];
    }

    public function getUser()
    {
        if ($this->user_id) {
            return User::findOne(['id' => $this->user_id]);
        }
        return null;
    }

}
