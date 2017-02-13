<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class TaskBuilder
{
    public function build(array $taskData)
    {
        $children = [];
        if (isset($taskData['children'])) {
            $children = array_map(function (array $taskData) {
                return $this->build($taskData);
            }, $taskData['children']);
        }

        switch ($taskData['type']) {
            case AndTask::TASK_NAME:
                return new AndTask($children);
            case OrTask::TASK_NAME:
                return new OrTask($children);
            case EqualToTask::TASK_NAME:
                return new EqualToTask($taskData['id'], $taskData['value']);
            case LessThanTask::TASK_NAME:
                return new LessThanTask($taskData['id'], $taskData['value']);
            case MoreThanTask::TASK_NAME:
                return new MoreThanTask($taskData['id'], $taskData['value']);
        }

        throw new \InvalidArgumentException(sprintf('Cannot build task with type: %s', $taskData['type']));
    }
}