<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'In behandeling';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Opdracht verstuurd</h1>
    </div>

    <div class="body-content">
        <?= Html::beginForm(['query/result', 'request' => $request], 'post'); ?>
        <div class="row">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
                <h2 id="title">Wachten op KNMI...</h2>

                <p>
                <div class="form-group">
                    <?= Html::submitButton('Doorgaan', ['class' => 'btn btn-primary', 'id' => 'continue', 'disabled' => 'disabled']) ?>
                </div>
                </p>
            </div>
            <div class="col-lg-4">
            </div>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>

<script>
    delayButton();
    function delayButton()
    {
        setTimeout(function() {
                document.getElementById("continue").removeAttribute("disabled");
                document.getElementById("title").innerHTML = "Klaar!";
            },
            5000
        );
    }
</script>