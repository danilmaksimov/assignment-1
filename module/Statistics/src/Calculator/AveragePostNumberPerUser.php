<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostNumberPerUser extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int[]
     */
    private $postCountPerUser = [];

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $key = $postTo->getAuthorId();
        $this->postCountPerUser[$key] = ($this->postCountPerUser[$key] ?? 0) + 1;
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $userCount = count($this->postCountPerUser);
        $postCount = array_sum($this->postCountPerUser);
        $value = $userCount > 0
            ? $postCount / $userCount
            : 0;

        return (new StatisticsTo())->setValue(round($value,2));
    }
}
