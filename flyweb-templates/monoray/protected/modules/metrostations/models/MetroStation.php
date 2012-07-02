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

class MetroStation extends CActiveRecord{
	static $_activeStations = null;
	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{apartment_metro_station}}';
	}

	public function rules(){
		return array(
			array('name_ru', 'required'),
			array('name_ru', 'length', 'max'=>255),
			array('id, name_ru, date_updated', 'safe', 'on'=>'search'),
			array('class, coords', 'safe'),
		);
	}

	public function relations(){
		return array(
		);
	}

	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'name_ru' => tt('Station name (Russian)'),
			'date_updated' => 'Date Updated',
			'class' => tt('CSS class(es) (for map)'),
			'coords' => tt('Coordinates of station (for map)'),
		);
	}

	public function behaviors(){
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => null,
				'updateAttribute' => 'date_updated',
			),
		);
	}

	public function search(){
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('date_updated',$this->date_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
			'sort'=>array(
				'defaultOrder'=>'name_'.Yii::app()->language.' ASC',
			)
		));
	}

	public function stationTitle(){
		$tmp = 'name_'.Yii::app()->language;
		return $this->$tmp;
	}

	public function afterDelete(){
		$sql = 'DELETE FROM {{apartment_metro}} WHERE id_station="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		return parent::afterDelete();
	}

	public static function getAllStations(){
		$sql = 'SELECT id, name_'.Yii::app()->language.' as title FROM {{apartment_metro_station}} ORDER BY title';

		$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment_metro_station}}');

		$results = Yii::app()->db->cache(param('cachingTime', 1209600), $dependency)->createCommand($sql)->queryAll();
		$return = array();
		if($results){
			foreach($results as $result){
				$return[$result['id']] = $result['title'];
			}
		}
		return $return;
	}

	public static function getActiveStations(){
		if(self::$_activeStations === null){
			$sql = 'SELECT ms.name_'.Yii::app()->language.' as title, ms.id as id
				FROM {{apartment}} ap, {{apartment_metro_station}} ms, {{apartment_metro}} am
				WHERE am.id_apartment = ap.id AND am.id_station = ms.id';

			$cachingTime = param('shortCachingTime', 3600*4);
			$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment}}');

			$results = Yii::app()->db->createCommand($sql)->queryAll();

			self::$_activeStations = CHtml::listData($results, 'id', 'title');
		}
		return self::$_activeStations;
	}

	public static function stationsTitle($metroStations) {
		$return = null;
		if($metroStations){
			$metroStations = array_map("intval", $metroStations);
			$sql = 'SELECT name_'.Yii::app()->language.' AS title FROM {{apartment_metro_station}} WHERE id IN('.implode(',', $metroStations).')';
			$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment_metro_station}}');
			$result = Yii::app()->db->cache(param('cachingTime', 1209600), $dependency)->createCommand($sql)->queryColumn();
			if($result){
				$return = implode(', ', $result);
			}
		}

		return $return;
	}

	public static function getApartmentsIds($ids){
		$apartmentIds = array();
		if($ids){
			$ids = array_map("intval", $ids);
			$sql = 'SELECT DISTINCT id_apartment FROM {{apartment_metro}} WHERE id_station IN ('.implode(',', $ids).')';

			$dependency = new CDbCacheDependency('
				SELECT MAX(val) FROM
					(SELECT MAX(date_updated) as val FROM {{apartment}}
					UNION
					SELECT MAX(date_updated) as val FROM {{apartment_metro_station}}) as t
			');

			$apartmentIds = Yii::app()->db->cache(param('cachingTime', 1209600), $dependency)->createCommand($sql)->queryColumn();
		}
		return $apartmentIds;
	}
}