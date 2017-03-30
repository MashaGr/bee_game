<?php

namespace tests\unit;

use app\models\Hive;

class HiveConfigTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testNotValidConfig()
    {
        $hivePopulation = [
            'queen' => [
                'amount' => 1,
            ],
        ];

        $hive = new Hive();
        $this->assertFalse($hive->isValidHiveConfig($hivePopulation));
    }

    public function testZeroAmount()
    {
        $hivePopulation = [
            'queen' => [
                'amount' => 0,
                'lifespan' => 100,
                'hitValue' => 8,
            ],
            'worker' => [
                'amount' => 5,
                'lifespan' => 75,
                'hitValue' => 10,
            ],
            'drone' => [
                'amount' => 0,
                'lifespan' => 50,
                'hitValue' => 12,
            ],
        ];

        $hive = new Hive();
        $this->assertTrue($hive->isValidHiveConfig($hivePopulation));
    }

    public function testNegativeLifespan()
    {
        $hivePopulation = [
            'queen' => [
                'amount' => 0,
                'lifespan' => 100,
                'hitValue' => 8,
            ],
            'drone' => [
                'amount' => 15,
                'lifespan' => -50,
                'hitValue' => 12,
            ],
        ];

        $hive = new Hive();
        $this->assertFalse($hive->isValidHiveConfig($hivePopulation));
    }

    public function testNegativeHitValue()
    {
        $hivePopulation = [
            'queen' => [
                'amount' => 0,
                'lifespan' => 100,
                'hitValue' => -8,
            ],
            'drone' => [
                'amount' => 15,
                'lifespan' => 50,
                'hitValue' => -12,
            ],
        ];

        $hive = new Hive();
        $this->assertFalse($hive->isValidHiveConfig($hivePopulation));
    }
}
