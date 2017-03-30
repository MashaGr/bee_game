<?php

namespace tests\unit;

use app\models\Hive;

class HiveTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testHiveBehavior()
    {
        $hive = new Hive();

        $this->assertFalse($hive->issetHive());

        $this->assertNotFalse($hive->getHive());

        $this->assertNotFalse($hive->beeCatchHit());

        $this->assertNotFalse($hive->beeCatchHit('random'));

        $this->assertFalse($hive->beeCatchHit('1234567'));
    }
}
