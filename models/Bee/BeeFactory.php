<?php

namespace app\models\Bee;


class BeeFactory {

    public static function getBee($beeType, $lifespan, $hitValue) {
        switch ($beeType) {
            case 'queen':
                return new BeeQueen($beeType, $lifespan, $hitValue);
                break;
            case 'worker':
                return new BeeWorker($beeType, $lifespan, $hitValue);
                break;
            case 'drone':
                return new BeeDrone($beeType, $lifespan, $hitValue);
                break;
        }
    }
}
