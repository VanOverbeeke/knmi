<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Query extends Model
{
	public $start;
	public $end;
	public $inseason;
	public $vars;
	public $stns;

	public function rules()
	{
		return [
			['start', 'date', 'format' => 'php:Y-m-d'],
			['end', 'date'],
			['inseason', 'boolean'],
			['vars', 'string'],
			['stns', 'string'],
		];
	}
}
