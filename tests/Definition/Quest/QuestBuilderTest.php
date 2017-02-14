<?php

namespace LittleCubicleGames\Tests\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class QuestBuilderTest extends TestCase
{
    /** @var QuestBuilder */
    private $builder;
    private $task;
    private $taskBuilder;

    protected function setUp()
    {
        $this->task = $this->getMockBuilder(TaskInterface::class)->getMock();

        $this->taskBuilder = $this->getMockBuilder(TaskBuilder::class)->getMock();
        $this->builder = new QuestBuilder($this->taskBuilder);
    }

    public function testBuild()
    {
        $data = [
            'id' => 1,
            'task' => $task = [],
        ];

        $this->taskBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($task))
            ->willReturn($this->task);

        $quest = $this->builder->build($data);

        $this->assertInstanceOf(Quest::class, $quest);
        $this->assertSame(1, $quest->getId());
        $this->assertSame($this->task, $quest->getTask());
    }
}
