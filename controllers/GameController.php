<?php

namespace app\controllers;

use Yii;
use yii\base\Controller;
use yii\web\Response;
use yii\base\Exception;
use app\models\Hive;

class GameController extends Controller {

    /**
     * @return string
     */
    public function actionIndex() {
        $sessionStatus = 'start';
        $beesArray = [];
        $hive = new Hive();

        if ($hive->issetHive()) {
            $sessionStatus = 'continue';
            $beesArray = $hive->getHive();
        }

        return $this->render('index', [
            'sessionStatus' => $sessionStatus,
            'beesArray' => $beesArray,
        ]);
    }

    /**
     * @return array|bool
     */
    public function actionStartGame() {
        try {
            if (Yii::$app->request->isAjax) {
                $hive = new Hive();
                $hive->destroyHive();
                $beesArray = $hive->getHive();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'result' => $beesArray ? 'success' : 'empty_hive',
                    'objects' => $beesArray,
                ];
            }
            return false;
        }
        catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * @return array|bool
     */
    public function actionStopGame() {
        try {
            if (Yii::$app->request->isAjax) {
                $hive = new Hive();
                $hive->destroyHive();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'result' => 'success',
                ];
            }
            return false;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }

    /**
     * @return array|bool
     */
    public function actionBeeGotHit() {
        try {
            if (Yii::$app->request->isAjax && Yii::$app->request->post('object_id')) {
                $beeId = Yii::$app->request->post('object_id');
                $hive = new Hive();
                $result = $hive->beeCatchHit($beeId);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $result;
            }
            return false;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage();
        }
    }
}
