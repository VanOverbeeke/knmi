<?php

namespace app\models;

use yii\db\ActiveRecord;

class Query extends ActiveRecord
{
	public $start;
	public $end;
	public $inseason;
	public $vars;
	public $stns;
	public $done;

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{query}}';
    }

	public function rules()
	{
		return [
			[['start'], 'date', 'format' => 'php:Y-m-d'],
			[['end'], 'date', 'format' => 'php:Y-m-d'],
			[['inseason'], 'boolean'],
			[['vars'], 'string'],
			[['stns'], 'string'],
			[['start', 'end', 'inseason', 'vars', 'stns', 'done'], 'safe'],
		];
	}

	public function getVars() {
	    return ['TG' => 'Temp G', 'TN' => 'Temp N', 'TX' => 'Temp X', 'T10N' => 'Temp10'];
    }

    public function getStns() {
        return ['260' => 'De Bilt', '280' => 'Stad X'];
    }
}
