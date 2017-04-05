<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Progress\Functions\InitProgressHandlerFunctionInterface;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Workflow\Workflow;

class ProgressHandler
{
    /** @var Workflow */
    private $worfkflow;
    /** @var QuestStorageInterface */
    private $questStorage;
    public function __construct(Workflow $worfkflow, QuestStorageInterface $questStorage)
    {
        $this->worfkflow = $worfkflow;
        $this->questStorage = $questStorage;
    }
    public function handle(QuestInterface $quest, $taskId, callable $handler, Event $event)
    {
        $task = $quest->getTask($taskId);
        $progress = call_user_func($handler, $task, $event);
        $task->updateProgress($progress);
        if ($this->worfkflow->can($quest, QuestDefinitionInterface::TRANSITION_COMPLETE)) {
            $this->worfkflow->apply($quest, QuestDefinitionInterface::TRANSITION_COMPLETE);
        }
        $this->questStorage->save($quest);
    }

    public function initProgress(QuestInterface $quest, $taskId, InitProgressHandlerFunctionInterface $handler)
    {
        $task = $quest->getTask($taskId);
        $progress = $handler->initProgress($quest, $task);
        $task->updateProgress($progress);

        if ($this->worfkflow->can($quest, QuestDefinitionInterface::TRANSITION_COMPLETE)) {
            $this->worfkflow->apply($quest, QuestDefinitionInterface::TRANSITION_COMPLETE);
        }

        $this->questStorage->save($quest);
    }
}
