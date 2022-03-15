<?php

declare(strict_types = 1);

namespace Tests\unit\Statistics\Calculator;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialPost\Hydrator\FictionalPostHydrator;
use Statistics\Calculator\AveragePostNumberPerUser;
use Statistics\Dto\ParamsTo;
use Statistics\Enum\StatsEnum;

class AveragePostNumberPerUserTest extends TestCase
{

    public function testCalculation()
    {
        $calculator = new AveragePostNumberPerUser();
        $params = (new ParamsTo())
            ->setStatName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
            ->setStartDate((new DateTime('2018-08-01T00:00:00+00:00')))
            ->setEndDate((new DateTime('2018-08-31T23:59:59+00:00')));
        $calculator->setParameters($params);
        $posts = $this->getPostsTo();
        foreach ($posts as $post) {
            $calculator->accumulateData($post);
        }
        $this->assertEquals(1, $calculator->calculate()->getValue());
    }

    /**
     * @return array
     */
    private function getPostsTo(): array
    {
        $postsTo = [];
        $socialRostsResponse = json_decode(
            file_get_contents($_ENV['TEST_DATA_PATH'] . 'social-posts-response.json'),
            true
        );
        $hydrator = new FictionalPostHydrator();
        foreach ($socialRostsResponse['data']['posts'] as $post) {
            $postsTo[] = $hydrator->hydrate($post);
        }

        return $postsTo;
    }

}