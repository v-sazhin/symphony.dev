<?php

namespace Sazhin\BlogBundle\DataFixtures\ORM;

use Nelmio\Alice\Fixtures;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommonData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__ . '/fixtures.yml', $manager);
    }

}