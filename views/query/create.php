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
                <h2>Periode</h2>

                <p>
                    <label>Begindatum</label>
                    <?= $form->field($query, 'start')->textInput(); ?>
                    <label>Einddatum</label>
                    <?= $form->field($query, 'end')->textInput(); ?>
                    <label>Seizoen?</label>
                    <?= $form->field($query, 'inseason')->checkbox([
                        'label'=>'',
                    ]);
                    ?>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Datatypen</h2>

                <p>
                    <?php echo $form->field($query, 'vars')->radioList(\app\models\Query::getVars()); ?>
                </p>

            </div>
            <div class="col-lg-4">
                <h2>Stations</h2>

                <p>
                    <?php echo $form->field($query, 'stns')->radioList(\app\models\Query::getStns()); ?>
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
