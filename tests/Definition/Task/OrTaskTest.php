<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\OrTask;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class OrTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished($value, $expected)
    {
        $taskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $taskMock->method('isFinished')->willReturn($value);
        $task = new OrTask(array($taskMock));
        $this->assertSame($expected, $task->isFinished(array()));
    }
    public function isFinishedProvider()
    {
        return array(array(false, false), array(true, true));
    }
    public function testIsFinishedMultiple()
    {
        $successTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $successTaskMock->method('isFinished')->willReturn(true);
        $failTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $failTaskMock->method('isFinished')->willReturn(false);
        $task = new OrTask(array($successTaskMock, $failTaskMock));
        $this->assertTrue($task->isFinished(array()));
        $task = new OrTask(array($failTaskMock, $successTaskMock));
        $this->assertTrue($task->isFinished(array()));
        $task = new OrTask(array($successTaskMock, $successTaskMock));
        $this->assertTrue($task->isFinished(array()));
    }
    public function testGetTaskIdTypesEmpty()
    {
        $task = new OrTask(array());
        $this->assertSame(array(), $task->getTaskIdTypes());
    }
    public function testGetTaskIdTypes()
    {
        $task1Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task1Mock->method('getTaskIdTypes')->willReturn(array(1 => 'type1'));
        $task2Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task2Mock->method('getTaskIdTypes')->willReturn(array(2 => 'type2'));
        $task = new OrTask(array($task1Mock, $task2Mock));
        $this->assertEquals(array(1 => 'type1', 2 => 'type2'), $task->getTaskIdTypes());
        $task = new OrTask(array($task2Mock, $task1Mock));
        $this->assertEquals(array(2 => 'type2', 1 => 'type1'), $task->getTaskIdTypes());
    }
}
