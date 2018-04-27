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
                    <?= $form->field($model, 'start')->textInput(); ?>
                    <label>Einddatum</label>
                    <?= $form->field($model, 'end')->textInput(); ?>
                    <label>Seizoen?</label>
                    <?= $form->field($model, 'inseason')->checkbox([
                        'label'=>'',
                    ]);
                    ?>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Datatypen</h2>

                <p>
                    <?php echo $form->field($model, 'vars')->checkboxList(\app\models\Query::getVars()); ?>
                </p>

            </div>
            <div class="col-lg-4">
                <h2>Stations</h2>

                <p>
                    <?php echo $form->field($model, 'stns')->checkboxList(\app\models\Query::getStns()); ?>
                </p>

                <p>
                    <div class="form-group">
                        <?= Html::submitButton('Versturen', ['class' => 'btn btn-primary']) ?>
                    </div>
                </p>
            </div>
        </div>
        <?php ActiveForm::end(); ?></p>
    </div>
</div>
