<?php

namespace app\models\Bee;


class BeeWorker extends Bee {

    public function __construct($beeType, $lifespan, $hitValue) {
        parent::__construct($beeType, $lifespan, $hitValue);
    }
}
