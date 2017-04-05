<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;

interface ProgressFunctionBuilderInterface
{
    public function build($taskName, array $attributes): ?HandlerFunctionInterface;
}
