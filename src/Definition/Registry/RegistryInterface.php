<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

interface RegistryInterface
{
    public function getQuest($id): Quest;
    public function getNextQuest(Slot $slot, ?QuestInterface $quest = null): ?Quest;
}
