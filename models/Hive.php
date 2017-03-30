<?php

namespace app\models;

use app\models\Bee\Bee;
use app\models\Bee\BeeWorker;
use Yii;
use yii\base\Exception;
use app\models\Bee\BeeFactory;

class Hive {

    /**
     * @var array
     */
    private $hivePopulation = [];

    /**
     * @return bool
     */
    public function issetHive() {
        $session = Yii::$app->session;
        if ($session->has('beesArray') && $this->getAliveBees($session->get('beesArray'))) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getHive() {
        try {
            $session = Yii::$app->session;
            if (!$this->issetHive()) {
                $beesArray = $this->createHive();
                $session->set('beesArray', $beesArray);
            }
            return $session->get('beesArray');
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * @return bool
     */
    public function destroyHive() {
        $session = Yii::$app->session;
        if ($session->has('beesArray')) {
            $session->remove('beesArray');
        }
        return true;
    }

    /**
     * @param string $beeId
     * @return array|bool
     */
    public function beeCatchHit($beeId = 'random') {
        try {
            $beesArray = $this->getHive();
            $result = false;

            if ($beeId !== 'random') {
                foreach ($beesArray as $key => $bee) {
                    if ($bee['id'] === $beeId) {
                        $result = $this->changeBeeAfterHit($bee);
                        break;
                    }
                }
            } else {
                $beesAliveIndexArray = $this->getAliveBees($beesArray);
                $beeIndex = array_rand($beesAliveIndexArray, 1);
                $result = $this->changeBeeAfterHit($beesArray[$beeIndex]);
                $result['bee'] = $beesArray[$beeIndex];
            }

            return $result;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * @param array $hivePopulation
     * @return bool
     */
    public function isValidHiveConfig($hivePopulation = []) {
        try {
            // If default parameter $hivePopulation is empty
            // check existence of the array with required parameters in config/params.php file
            if ( (!is_array($hivePopulation) || empty($hivePopulation))
                && isset(Yii::$app->params['hivePopulation']) && is_array(Yii::$app->params['hivePopulation'])
                && !empty(Yii::$app->params['hivePopulation']) ) {
                $hivePopulation = Yii::$app->params['hivePopulation'];
            }
            // Check the structure of the config
            if ($hivePopulation) {
                foreach ($hivePopulation as $hiveMember) {
                    if (!isset($hiveMember['amount']) || $hiveMember['amount'] < 0
                        || !isset($hiveMember['lifespan']) || $hiveMember['lifespan'] < 0
                        || !isset($hiveMember['hitValue']) || $hiveMember['hitValue'] < 0
                    ) {
                        return false;
                    }
                }
                $this->hivePopulation = $hivePopulation;
                return true;
            }
            return false;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * @return array|bool
     */
    public function createHive() {
        try {
            if ($this->isValidHiveConfig()) {
                $hive = [];
                $index = 0;

                foreach ($this->hivePopulation as $hiveMemberType => $hiveMemberConfig) {
                    $hiveMemberAmount = $hiveMemberConfig['amount'];
                    while ($hiveMemberAmount > 0) {
                        $hive[] = [
                            'object' => BeeFactory::getBee($hiveMemberType, $hiveMemberConfig['lifespan'], $hiveMemberConfig['hitValue']),
                            'id' => "$hiveMemberType.$index",
                        ];

                        $hiveMemberAmount--;
                        $index++;
                    }
                }

                shuffle($hive);
                return $hive;
            }
            return false;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * Keys of return array are the keys of session arr with alive bees
     * @param array $beesArray
     * @return array
     */
    private function getAliveBees($beesArray) {
        $beesAliveIndexArray = [];

        foreach ($beesArray as $key => $bee) {
            if ($bee['object']->getCurrentLifespan() > 0) {
                $beesAliveIndexArray[$key] = $bee['id'];
            }
        }

        return $beesAliveIndexArray;
    }

    /**
     * @param array $bee
     * @return array
     */
    private function changeBeeAfterHit($bee) {
        try {
            $session = Yii::$app->session;
            $beesArray = $session->get('beesArray');
            $deadAmount = false;

            if (isset($bee['object']) && ($bee['object'] instanceof Bee)) {
                $bee['object']->catchHit();
                $updLifespan = ($bee['object']->getCurrentLifespan() > 0) ? $bee['object']->getCurrentLifespan() : 0;

                if ($updLifespan === 0) {
                    switch ($bee['object']->getBeeType()) {
                        case "queen":
                            foreach ($beesArray as $key => $subBee) {
                                $subBee['object']->setCurrentLifespan(0);
                                $subBee['object']->setBeeType('dead');
                            }
                            $deadAmount = 'all';
                            $session->set('beesArray', $beesArray);
                            break;
                        default:
                            $deadAmount = 'one';
                            $bee['object']->setBeeType('dead');
                    }
                }

                return [
                    'result' => 'success',
                    'dead_amount' => $deadAmount,
                    'upd_lifespan' => $updLifespan,
                ];
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage();
        }
    }
}
