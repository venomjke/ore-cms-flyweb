<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	1.2.0
*	copyright			:	(c) 2012 Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class Paysystem extends CActiveRecord{
	public static $translate = array(
		'robokassa' => 'Робокасса',
		'offline' => 'Платеж через банк',
	);

	public $translatedName;

	public static function translatePaysystem($name){
		if(isset(self::$translate[$name])){
			return self::$translate[$name];
		} else {
			return $name;
		}
	}

	const STATUS_ACTIVE=1;
	const STATUS_INACTIVE=0;
	const MODE_REAL=1;
	const MODE_TEST=0;

	public $payModel = null;
	public $payModelName = null;
	public $viewName = null;

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{paysystem}}';
	}

	public function rules(){
		return array(
			array('status', 'required'),
        );
	}

	protected function afterFind(){
		// создаем зависимые модели
		$this->createPayModel();
		// создаем "человеческое" название платежной системы
		$this->translatedName = $this->translatePaysystem($this->name);
		return parent::afterFind();
	}

	protected function beforeSave(){
		$settings = array();
		foreach($this->payModel->attributes as $key => $value) {
			$settings[$key] = $value;
		}
		// Сохраняем аттрибуты зависимой модели (настройки платежки)
		$this->settings = CJSON::encode($settings);

		return parent::beforeSave();
	}

	public function attributeLabels(){
		return array(
			'status' => Yii::t('module_payment','Status'),
		);
	}

	public function createPayModel(){
		if($this->name && !$this->payModel){
			$this->payModelName = ucfirst($this->name);
			$this->payModel = new $this->payModelName;
			$this->viewName = $this->name;
			$this->payModel->attributes = CJSON::decode($this->settings, true);
		}
		return $this->payModel;
	}

	public static function getPaysystems($all = null){
		if($all){
			$models = Paysystem::model()->findAll();
		} else {
			$models = Paysystem::model()->findAllByAttributes(array('status' => Paysystem::STATUS_ACTIVE));
		}

		return $models;
	}
}