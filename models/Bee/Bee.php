<?php

namespace app\models\Bee;

class Bee {
    public $beeType;
    public $initialLifespan;
    public $currentLifespan;
    public $hitValue;

    public function __construct($beeType, $lifespan, $hitValue) {
        $this->beeType = $beeType;
        $this->initialLifespan = $lifespan;
        $this->currentLifespan = $lifespan;
        $this->hitValue = $hitValue;
    }

    public function setInitialLifespan($initialLifespan) {
        $this->initialLifespan = $initialLifespan;
    }

    public function getInitialLifespan() {
        return $this->initialLifespan;
    }

    public function setCurrentLifespan($currentLifespan) {
        $this->currentLifespan = $currentLifespan;
    }

    public function getCurrentLifespan() {
        return $this->currentLifespan;
    }

    public function setHitValue($hitValue) {
        $this->hitValue = $hitValue;
    }

    public function getHitValue() {
        return $this->hitValue;
    }

    public function setBeeType($beeType) {
        $this->beeType = $beeType;
    }

    public function getBeeType() {
        return $this->beeType;
    }

    public function catchHit() {
        $this->currentLifespan = $this->currentLifespan - $this->hitValue;
    }
}
