<?php

namespace app\models\Bee;


class BeeDrone extends Bee {

    public function __construct($beeType, $lifespan, $hitValue) {
        parent::__construct($beeType, $lifespan, $hitValue);
    }
}
