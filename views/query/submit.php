<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'KNMI Aanvraag';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Nieuwe opdracht</h1>
    </div>

    <div class="body-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-4">
                <h2>Query String</h2>

                <p>
                    <label>Query</label>
                    <?= $form->field('queryString', $queryString)->textInput(); ?>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Datatypen</h2>

                <p>
                    x
                </p>

            </div>
            <div class="col-lg-4">
                <h2>Stations</h2>

                <p>
                    x
                </p>

                <p>
                    <div class="form-group">
                        <?= Html::submitButton('Versturen', ['class' => 'btn btn-primary']) ?>
                    </div>
                </p>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
