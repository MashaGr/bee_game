<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Game';
?>


<?php if ($sessionStatus === 'start') : ?>
    <?= Html::button('Start', ['id' => 'start_game_button', 'class' => 'btn btn-success']); ?>
    <div class="wrapper">
        <div class="row"></div>
    </div>
<?php endif; ?>

<?php if ($sessionStatus === 'continue') : ?>
    <div class="nav-stop-block">
        <?= Html::button('Stop', ['id' => 'stop_game_button', 'class' => 'btn btn-danger']); ?>
        <?= Html::button('Hit', ['id' => 'hit_bee_button', 'class' => 'btn btn-warning']); ?>
    </div>
    <div class="wrapper">
        <div class="row">
        <?php foreach ($beesArray as $bee) : ?>
            <div class="col-md-1 col-bee-white"></div>
            <div class="col-md-1 col-bee-<?= $bee['object']->getBeeType(); ?>" data-bee-id="<?= $bee['id']; ?>">
                <h5><?= ($bee['object']->getCurrentLifespan() > 0) ? $bee['object']->getCurrentLifespan() : ''; ?></h5>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>