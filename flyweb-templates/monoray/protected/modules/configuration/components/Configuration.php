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

class Configuration extends CComponent {
	
	public $cachingTime; 
	public static $cacheName = 'module_configuration_model';

	public function init(){
		$this->cachingTime = param('cachingTime', 5184000); // default caching for 60 days
		if (file_exists(ALREADY_INSTALL_FILE)) {
			$this->loadConfig();
		}
	}

	private function loadConfig() {
		Yii::import('application.modules.configuration.models.ConfigurationModel');
		$model = Yii::app()->cache->get(self::$cacheName);
		if($model === false) {
			$model = ConfigurationModel::model()->findAll();
			Yii::app()->cache->set(self::$cacheName, $model, $this->cachingTime);
		}
		foreach ($model as $key) {
			Yii::app()->params[$key->name] = $key->value;
		}
	}

	public static function clearCache(){
		Yii::app()->cache->delete(self::$cacheName);
	}
}
