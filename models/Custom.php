<?php

namespace app\models;

use \Yii;

class Custom
{
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';
    const KNMI_FORMAT = 'php:Ymd';
    const JS_FORMAT = 'YYYY-MM-DD';

    public static function dateFormat($dateStr, $type='date', $format = null) {
        if ($type === 'datetime') {
            $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
            $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        elseif ($type === 'knmi') {
            $fmt = ($format == null) ? self::KNMI_FORMAT : $format;
        }
        else {
            $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return Yii::$app->formatter->asDate($dateStr, $fmt);
    }
}

