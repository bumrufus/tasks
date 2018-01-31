<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 01.07.2017
 * Time: 17:18
 */

namespace humhub\modules\task\models\forms;


use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\task\models\Task;
use humhub\modules\task\models\TaskAssigned;
use humhub\modules\task\permissions\ManageTasks;
use Yii;
use yii\base\Model;

class TaskFilter extends Model
{
    /**
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $past = 1;

    /**
     * @var int
     */
    public $taskUser;

    /**
     * @var int
     */
    public $own;

    public function rules()
    {
        return [
            ['title', 'string'],
            [['past', 'taskUser', 'own'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('TaskModule.models_forms_TaskFilter', 'Filter tasks'),
            'past' => Yii::t('TaskModule.models_forms_TaskFilter', 'Only past tasks'),
            'taskUser' => Yii::t('TaskModule.models_forms_TaskFilter', 'I\'m working on this task'),
            'own' => Yii::t('TaskModule.models_forms_TaskFilter', 'Created by me'),
        ];
    }

    public function query()
    {
        $user = Yii::$app->user->getIdentity();

        if($this->past) {
            $query = Task::findPastTasks($this->contentContainer);
        } else {
            $query = Task::findReadable($this->contentContainer);
        }

        if(!empty($this->title)) {
            $query->andWhere(['like', 'title', $this->title]);
        }

        if($this->taskUser) {
            $subQuery = TaskAssigned::find()
                ->where('task_assigned.task_id=task.id')
                ->andWhere(['task_assigned.user_id' => $user->id]);
            $query->andWhere(['exists', $subQuery]);
        }

        if($this->own) {
            $query->andWhere(['content.created_by' => $user->contentcontainer_id]);
        }



        return $query;
    }
}