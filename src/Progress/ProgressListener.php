<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ProgressListener implements EventSubscriberInterface
{
    /** @var RegistryInterface */
    private $questRegistry;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var ProgressHandler */
    private $questProgressHandler;
    /** @var ProgressFunctionBuilderInterface */
    private $progressFunctionBuilder;
    /** @var array[] */
    private $questListenerMap = array();
    public function __construct(RegistryInterface $questRegistry, EventDispatcherInterface $dispatcher, ProgressHandler $questProgressHandler, ProgressFunctionBuilderInterface $progressFunctionBuilder)
    {
        $this->questRegistry = $questRegistry;
        $this->dispatcher = $dispatcher;
        $this->questProgressHandler = $questProgressHandler;
        $this->progressFunctionBuilder = $progressFunctionBuilder;
    }
    public function subscribeQuest(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();
        $this->registerQuest($quest);
    }
    public function unsubscribeQuest(Event $event)
    {
        /** @var QuestInterface $quest */
        $quest = $event->getSubject();
        $listeners = call_user_func(function ($v1, $v2) {
            return isset($v1) ? $v1 : $v2;
        }, @$this->questListenerMap[$quest->getQuestId()], @array());
        foreach ($listeners as $eventName => $listener) {
            $this->dispatcher->removeListener($eventName, $listener);
        }
        unset($this->questListenerMap[$quest->getQuestId()]);
    }
    public function registerQuest(QuestInterface $quest)
    {
        $questData = $this->questRegistry->getQuest($quest->getQuestId());
        $taskMap = $questData->getTask()->getTaskIdTypes();
        foreach ($taskMap as $taskId => $type) {
            $handlerFunction = $this->progressFunctionBuilder->build($type);
            foreach ($handlerFunction->getEventMap() as $eventName => $method) {
                $callback = array($handlerFunction, $method);
                $listener = function (\Symfony\Component\EventDispatcher\Event $event) use ($quest, $taskId, $callback) {
                    $this->questProgressHandler->handle($quest, $taskId, $callback, $event);
                };
                $this->questListenerMap[$quest->getQuestId()][$eventName] = $listener;
                $this->dispatcher->addListener($eventName, $listener);
            }
        }
    }
    public static function getSubscribedEvents()
    {
        return array(sprintf('workflow.%s.enter.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_START) => 'subscribeQuest', sprintf('workflow.%s.leave.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD) => 'unsubscribeQuest', sprintf('workflow.%s.leave.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_ABORT) => 'unsubscribeQuest', sprintf('workflow.%s.leave.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_REJECT) => 'unsubscribeQuest');
    }
}
