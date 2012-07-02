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

class Apartment extends ParentModel {
	public $title;
	
	public $metroStations;
	public $ownerEmail;
	private $_stationsTitle = 0;

    const TYPE_RENT = 1;
    const TYPE_SALE = 2;
    const TYPE_DEFAULT = 1;

    private static $_type_arr;

    const PRICE_SALE = 1;
    const PRICE_PER_HOUR = 2;
    const PRICE_PER_DAY = 3;
    const PRICE_PER_WEEK = 4;
    const PRICE_PER_MONTH = 5;

	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_MODERATION = 2;

    private static $_price_arr;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{apartment}}';
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

	public function rules() {
		return array(
			array('price_from_rur, title_ru', 'required'),
			array('price_from_rur, floor, floor_total, square, window_to, type, price_type, obj_type_id, city_id', 'numerical', 'integerOnly' => true),
			array('price_from_rur ', 'numerical', 'min' => 1),
			array('berths, title_ru', 'length', 'max' => 255),
			array('lat, lng', 'length', 'max' => 25),
			array('id', 'safe', 'on' => 'search'),
			array('floor', 'myFloorValidator'),
			array('owner_active, num_of_rooms, is_special_offer, is_free_from, is_free_to, active, address_ru, description_ru, description_near_ru, metroStations', 'safe'),
			array('city_id, owner_active, active, type, ownerEmail', 'safe', 'on' => 'search'),
		);
	}

	public function myFloorValidator($attribute,$params){
		if($this->floor && $this->floor_total){
			if($this->floor > $this->floor_total)
			$this->addError('floor', 'Текущий этаж не может быть больше, чем количество этажей в доме');
		}
	}

	public function relations() {
        Yii::import('application.modules.apartmentObjType.models.ApartmentObjType');
        Yii::import('application.modules.apartmentCity.models.ApartmentCity');
        return array(
			'objType' => array(self::BELONGS_TO, 'ApartmentObjType', 'obj_type_id'),

			'city' => array(self::BELONGS_TO, 'ApartmentCity', 'city_id'),

			'windowTo' => array(self::BELONGS_TO, 'WindowTo', 'window_to'),

			'images' => array(self::HAS_ONE, 'Galleries', 'pid'/*, 'select' => 'imgsOrder'*/),

			'comments' => array(self::HAS_MANY, 'Comment', 'apartment_id',
				'on' => 'comments.active = '.Comment::STATUS_APPROVED,
				'order' => 'comments.id DESC',
			),
			'commentCount' => array(self::STAT, 'Comment', 'apartment_id',
				'condition' => 'active=' . Comment::STATUS_APPROVED),
			
			'user' => array(self::BELONGS_TO, 'User', 'owner_id'),
		);
	}

	public function getUrl() {
		$tmp = 'title_'.Yii::app()->language;
		return Yii::app()->createUrl('/apartments/main/view', array(
			'id' => $this->id,
			'title' => $this->$tmp,
		));
	}

	public function attributeLabels() {
		return array(
			'id' => tt('ID', 'apartments'),
            'type' => tt('Type', 'apartments'),
			'price_from_rur' => tt('Price from (RUR per day)', 'apartments'),
			'price_from_usd' => tt('Price from (USD per day)', 'apartments'),
			'price_to_usd' => tt('Price to (USD per day)', 'apartments'),
			'num_of_rooms' => tt('Number of rooms', 'apartments'),
			'floor' => tt('Floor', 'apartments'),
			'floor_total' => tt('Total number of floors', 'apartments'),
			'square' => tt('Square', 'apartments'),
			'window_to' => tt('Window to', 'apartments'),
			'title_ru' => tt('Apartment title (Russian)', 'apartments'),
			'description_ru' => tt('Description (Russian)', 'apartments'),
			'description_near_ru' => tt('What is near? (Russian)', 'apartments'),
			'metro_station' => tt('Metro station', 'apartments'),
			'address_ru' => tt('Address (Russian)', 'apartments'),
			'special_offer' => tt('Special offer', 'apartments'),
			'berths' => tt('Number of berths', 'apartments'),
			'active' => tt('Status', 'apartments'),
			'metroStations' => tt('Nearest metro stations', 'apartments'),
			'is_free_from' => tt('Is free from', 'apartments'),
			'is_free_to' => tt('to', 'apartments'),
			'is_special_offer' => tt('Special offer', 'apartments'),
			'metroStation' => 'Станции метро',
            'obj_type_id' => tt('Object type', 'apartments'),
            'city_id' => tt('City', 'apartments'),
			'city' => tt('City', 'apartments'),
			'owner_active' => 'Статус (владелец)',
			'ownerEmail' => 'Разместил',
		);
	}

	public function search() {

		$criteria = new CDbCriteria;
		$tmp = 'title_'.Yii::app()->language;

		$criteria->compare($this->getTableAlias().'.id', $this->id);
		$criteria->compare($this->getTableAlias().'.active', $this->active, true);
		$criteria->compare('city_id', $this->city_id);
		$criteria->compare('type', $this->type);

		$criteria->compare($tmp, $this->$tmp, true);

		$criteria->with = array('user');

		if (issetModule('userads') && param('useModuleUserAds', 1)) {
					
			if ($this->ownerEmail) {
				$criteriaOwner = new CDbCriteria;
				$criteriaOwner->addCondition('email LIKE "%'.$this->ownerEmail.'%"');
				$userInfo = User::model()->find($criteriaOwner);				
				if ($userInfo && count($userInfo) > 0 && isset($userInfo->id)) {
					$criteria->compare('owner_id', $userInfo->id, true);
				}
			}
		}

		/*$criteria->join = 'LEFT JOIN {{apartment_metro}} am ON am.id_apartment = id';

        if(isset($_GET['Apartment']['metro_filter']) && $_GET['Apartment']['metro_filter']){
            $criteria->compare('am.id_station', $_GET['Apartment']['metro_filter']);
            $_GET['Apartment_page'] = 1;
        }*/

        /*if(isset($_GET['Apartment']['type_filter']) && $_GET['Apartment']['type_filter']){
            $criteria->compare('type', intval($_GET['Apartment']['type_filter']));
        }*/

		$criteria->order = $this->getTableAlias().'.sorter DESC';
		$criteria->with = array('city');

		

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			//'sort'=>array('defaultOrder'=>'sorter'),
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
		));
	}

	public function getPriceFrom(){
		if(Yii::app()->language == 'ru'){
			return $this->price_from_rur;
		}
	}

	public function getCurrency(){
		if(Yii::app()->language == 'ru'){
			return self::getPriceName($this->price_type);
		}
	}

	public function getStrByLang($str){
		$str .= '_'.Yii::app()->language;
		return $this->$str;
	}

	public static function getFullInformation($apartmentId, $type = Apartment::TYPE_DEFAULT){

        $addWhere = '';
        $addWhere .= (Apartment::TYPE_RENT == $type) ? ' AND reference_values.for_rent=1' : '';
        $addWhere .= (Apartment::TYPE_SALE == $type) ? ' AND reference_values.for_sale=1' : '';

		$sql = '
			SELECT	style,
					reference_categories.title_'.Yii::app()->language.' as category_title,
					reference_values.title_'.Yii::app()->language.' as value,
					reference_categories.id as ref_id,
					reference_values.id as ref_value_id
			FROM	{{apartment_reference}} reference,
					{{apartment_reference_categories}} reference_categories,
					{{apartment_reference_values}} reference_values
			WHERE	reference.apartment_id = "'.intval($apartmentId).'"
					AND reference.reference_id = reference_categories.id
					AND reference.reference_value_id = reference_values.id
					'.$addWhere.'
			ORDER BY reference_categories.sorter, reference_values.sorter';

		// Таблица apartment_reference меняется только при измении объявления (т.е. таблицы apartment)
		// Достаточно зависимости от apartment вместо apartment_reference
		$dependency = new CDbCacheDependency('
			SELECT MAX(val) FROM
				(SELECT MAX(date_updated) as val FROM {{apartment_reference_values}}
				UNION
				SELECT MAX(date_updated) as val FROM {{apartment_reference_categories}}
				UNION
				SELECT MAX(date_updated) as val FROM {{apartment}} WHERE id = "'.intval($apartmentId).'") as t
		');

		$results = Yii::app()->db->cache(param('cachingTime', 1209600), $dependency)->createCommand($sql)->queryAll();

		$return = array();
		foreach($results as $result){
			if(!isset($return[$result['ref_id']])){
				$return[$result['ref_id']]['title'] = $result['category_title'];
				$return[$result['ref_id']]['style'] = $result['style'];
			}
			$return[$result['ref_id']]['values'][$result['ref_value_id']] = $result['value'];
		}
		return $return;
	}
	
	public static function getCategories($id = null, $type = Apartment::TYPE_DEFAULT){
        $addWhere = '';
        $addWhere .= (Apartment::TYPE_RENT == $type) ? ' AND reference_values.for_rent=1' : '';
        $addWhere .= (Apartment::TYPE_SALE == $type) ? ' AND reference_values.for_sale=1' : '';

		$sql = '
			SELECT	style,
					reference_values.title_'.Yii::app()->language.' as value_title,
					reference_categories.title_'.Yii::app()->language.' as category_title,
					reference_category_id, reference_values.id
			FROM	{{apartment_reference_values}} reference_values,
					{{apartment_reference_categories}} reference_categories
			WHERE	reference_category_id = reference_categories.id
			'.$addWhere.'
			ORDER BY reference_categories.sorter, reference_values.sorter';

		$dependency = new CDbCacheDependency('
			SELECT MAX(val) FROM
				(SELECT MAX(date_updated) as val FROM {{apartment_reference_values}}
				UNION
				SELECT MAX(date_updated) as val FROM {{apartment_reference_categories}}) as t
		');

		$results = Yii::app()->db->cache(param('cachingTime', 1209600), $dependency)->createCommand($sql)->queryAll();

		$return = array();
		$selected = array();

		if($id){
			$selected = Apartment::getFullInformation($id, $type);
		}
		if($results){
			foreach($results as $result){
				$return[$result['reference_category_id']]['title'] = $result['category_title'];
				$return[$result['reference_category_id']]['style'] = $result['style'];
				$return[$result['reference_category_id']]['values'][$result['id']]['title'] = $result['value_title'];
				if(isset($selected[$result['reference_category_id']]['values'][$result['id']] )){
					$return[$result['reference_category_id']]['values'][$result['id']]['selected'] = true;
				}
				else{
					$return[$result['reference_category_id']]['values'][$result['id']]['selected'] = false;
				}
			}
		}
		return $return;
	}

	public function getMainThumb(){
		$images = array();
		if($this->images && $this->images->imgsOrder){
			$images = unserialize($this->images->imgsOrder);
			reset($images);
			return key($images);
		}
		return null;
	}
	
	public function getAllImages(){
		$images = array();
		if($this->images && $this->images->imgsOrder){
			$images = unserialize($this->images->imgsOrder);
			return array_keys($images);
		}
		return null;
	}

	public function saveCategories(){
		if(isset($_POST['category'])){
			$sql = 'DELETE FROM {{apartment_reference}} WHERE apartment_id="'.$this->id.'"';
			Yii::app()->db->createCommand($sql)->execute();

			foreach($_POST['category'] as $catId => $value){
				foreach($value as $valId => $val){
					$sql = 'INSERT INTO {{apartment_reference}} (reference_id, reference_value_id, apartment_id)
						VALUES (:refId, :refValId, :apId) ';
					$command = Yii::app()->db->createCommand($sql);
					$command->bindValue(":refId", $catId, PDO::PARAM_INT);
					$command->bindValue(":refValId", $valId, PDO::PARAM_INT);
					$command->bindValue(":apId", $this->id, PDO::PARAM_INT);
					$command->execute();
				}
			}
		}
	}

	public function beforeSave(){
		if(!$this->square){
			$this->square = 0;
		}
		
		if($this->isNewRecord){
			$this->owner_id = Yii::app()->user->id;
			
			$maxSorter = Yii::app()->db->createCommand()
				->select('MAX(sorter) as maxSorter')
				->from($this->tableName())
				->queryScalar();
			$this->sorter = $maxSorter+1;
		}

		return parent::beforeSave();
	}

	public function afterSave(){
		if($this->scenario == 'savecat'){
			$this->saveCategories();
            if($this->metroStations){
                $this->setMetroStations($this->metroStations);
            }
        }

        if($this->scenario != 'update_status'){
            // generate pdf
            Yii::import('application.modules.viewpdf.models.Viewpdf');
            Yii::app()->controller->widget('application.modules.viewpdf.components.viewPdfComponent',
                array('id' => $this->id, 'fromAdmin' => true));
        }

		return parent::afterSave();
	}

	public function beforeDelete(){
		
		$sql = 'DELETE FROM {{apartment_reference}} WHERE apartment_id="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		$sql = 'DELETE FROM {{apartment_comments}} WHERE apartment_id="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		$dir = Yii::getPathOfAlias('webroot.uploads.apartments') . '/'.$this->id;
		rrmdir($dir);

		$sql = 'DELETE FROM {{galleries}} WHERE pid="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		$sql = 'SELECT id FROM {{booking}} WHERE apartment_id="'.$this->id.'"';
		$bookings = Yii::app()->db->createCommand($sql)->queryColumn();

		if($bookings){
			$sql = 'DELETE FROM {{payments}} WHERE order_id IN ('.implode(',', $bookings).')';
			Yii::app()->db->createCommand($sql)->execute();
		}

		$sql = 'DELETE FROM {{booking}} WHERE apartment_id="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		$sql = 'DELETE FROM {{apartment_comments}} WHERE apartment_id="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();

		if (issetModule('metrostations')) {
			$sql = 'DELETE FROM {{apartment_metro}} WHERE id_apartment="'.$this->id.'"';
			Yii::app()->db->createCommand($sql)->execute();
		}
		
		// delete pdf file for apartment
		Yii::import('application.modules.viewpdf.models.Viewpdf');
		Yii::import('application.modules.viewpdf.components.viewPdfComponent');
		
		$viewPdf = new viewPdfComponent();
		$filePdf = $viewPdf->pdfCachePath.'/'.$viewPdf->filePrefix . $this->id . '.pdf';
		
		if (file_exists($filePdf)) {
			unlink($filePdf);
		}

		return parent::beforeDelete();
	}

	public function isValidApartment($id){
		$sql = 'SELECT id FROM {{apartment}} WHERE id = :id';
		$command = Yii::app()->db->createCommand($sql);
		return $command->queryScalar(array(':id' => $id));
	}
	
	public function getMetroStations(){
		$sql = 'SELECT id_station FROM {{apartment_metro}} WHERE id_apartment="'.$this->id.'"';
		
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}
	
	public function setMetroStations($stations){
		$sql = 'DELETE FROM {{apartment_metro}} WHERE id_apartment="'.$this->id.'"';
		Yii::app()->db->createCommand($sql)->execute();
		if(is_array($stations) && $stations){
			$values = array();
			foreach ($stations as $station) {
				$values[] = '(' . $station . ', ' . $this->id . ')';
			}

			if ($values) {
				$sql = 'INSERT INTO {{apartment_metro}} (id_station, id_apartment) VALUES ' . implode(',', $values);
				Yii::app()->db->createCommand($sql)->execute();
			}
		}
	}

	public function stationsTitle() {
        if (!issetModule('metrostations')) {
            return '';
        }

		if($this->_stationsTitle === 0){
			Yii::import('application.modules.metrostations.models.MetroStation');
			$this->metroStations = $this->getMetroStations();
			$this->_stationsTitle = MetroStation::stationsTitle($this->metroStations);
		}
		return $this->_stationsTitle;
	}

	public static function getFullDependency($id){
		return new CDbCacheDependency('
			SELECT MAX(val) FROM
				(SELECT MAX(date_updated) as val FROM {{apartment_comments}} WHERE apartment_id = "'.intval($id).'"
				UNION
				SELECT MAX(date_updated) as val FROM {{apartment}} WHERE id = "'.intval($id).'"
				UNION
				SELECT MAX(date_updated) as val FROM {{apartment_window_to}}
				UNION
				SELECT MAX(date_updated) as val FROM {{galleries}}) as t
		');
	}

	public static function getImagesDependency(){
		return new CDbCacheDependency('
			SELECT MAX(val) FROM
				(SELECT MAX(date_updated) as val FROM {{apartment}}
				UNION
				SELECT date_updated as val FROM {{galleries}}) as t
		');
	}

	public static function getDependency(){
		return new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment}}');
	}

	public static function getExistsRooms(){
		$sql = 'SELECT DISTINCT num_of_rooms FROM {{apartment}} WHERE active=1 AND num_of_rooms > 0 ORDER BY num_of_rooms';
		return Yii::app()->db->cache(param('cachingTime', 1209600), self::getDependency())->createCommand($sql)->queryColumn();
	}

    public static function getObjTypesArray($with_all = false){
        Yii::import('application.modules.apartmentObjType.models.ApartmentObjType');
        $objTypes = array();
        $objTypeModel = ApartmentObjType::model()->findAll(array(
            'order'=>'sorter'
        ));
        foreach($objTypeModel as $type){
            $objTypes[$type->id] = $type->name;
        }
        if($with_all){
            $objTypes[0] = tt('All object', 'apartments');
        }
        return $objTypes;
    }

    public static function getCityArray($with_all = false){
        Yii::import('application.modules.apartmentCity.models.ApartmentCity');
        $cityArr = array();
        $cityModel = ApartmentCity::model()->findAll(array(
            'order'=>'sorter'
        ));
        foreach($cityModel as $city){
            $cityArr[$city->id] = $city->name;
        }
        if($with_all){
            $cityArr[0] = tt('All city', 'apartments');
        }
        return $cityArr;
    }

    public static function getTypesArray($withAll = false){
        $types = array();
		
		if($withAll){
            $types[0] = tt('All', 'apartments');
        }
		
		$types[self::TYPE_RENT] = tt('Rent', 'apartments');
		$types[self::TYPE_SALE] = tt('Sale', 'apartments');

        return $types;
    }
	
	public static function getTypesWantArray() {
		$types = array();
		
		$types[self::TYPE_RENT] = Yii::t('common', 'rent apartment');
		$types[self::TYPE_SALE] = Yii::t('common', 'buy apartment');

        return $types;
	}

    public static function getNameByType($type){
        if(!isset(self::$_type_arr)){
            self::$_type_arr = self::getTypesArray();
        }
        return self::$_type_arr[$type];
    }

    public static function getPriceArray($type, $all = false, $with_all = false){
        if($all){
            return array(
                self::PRICE_SALE => tt('Sale price', 'apartments'),
                self::PRICE_PER_HOUR => tt('Price per hour', 'apartments'),
                self::PRICE_PER_DAY => tt('Price per day', 'apartments'),
                self::PRICE_PER_WEEK => tt('Price per week', 'apartments'),
                self::PRICE_PER_MONTH => tt('Price per month', 'apartments'),
            );
        }

        if($type == self::TYPE_SALE){
            $price = array(
                self::PRICE_SALE => tt('Sale price', 'apartments'),
            );
        }elseif($type == self::TYPE_RENT){
            $price = array(
                self::PRICE_PER_HOUR => tt('Price per hour', 'apartments'),
                self::PRICE_PER_DAY => tt('Price per day', 'apartments'),
                self::PRICE_PER_WEEK => tt('Price per week', 'apartments'),
                self::PRICE_PER_MONTH => tt('Price per month', 'apartments'),
            );
        }

        if($with_all){
            $price[0] = tt('All');
        }
        return $price;
    }

    public static function getPriceName($price_type){
        if(!isset(self::$_price_arr)){
            self::$_price_arr = self::getPriceArray(NULL, true);
        }
        return self::$_price_arr[$price_type];
    }

	public function getPrettyPrice(){
		$type = $this->type;
		$price = $this->getPriceFrom();


		if(!param('usePrettyPrice', 1)){
			return $price . ' ' . $this->getCurrency();
		}

		if (substr($price, -6) == "000000")
			$priceStr = substr_replace ($price, ' млн.', -6);
		elseif (substr($price, -5) == "00000" && strlen($price) >= 7) {
			$priceStr = substr_replace ($price, '.', -6, 0);
			$priceStr = substr_replace ($priceStr, ' млн.', -5);
		} elseif (substr($price, -3) == "000")
			$priceStr = substr_replace ($price, ' тыс.', -3);
		elseif (substr($price, -2) == "00" && strlen($price) >= 4) {
			$priceStr = substr_replace ($price, '.', -3, 0);
			$priceStr = substr_replace ($priceStr, ' тыс.', -2);
		} else $priceStr = $price.' ';


		$priceStr .= self::getPriceName($this->price_type);
        /*if($type == self::TYPE_SALE){
            $priceStr .= tt('Sale price', 'apartments');
        }elseif($type == self::TYPE_RENT){
            $priceStr .= tt('Price per month', 'apartments');
        }*/
        return $priceStr;
    }

	public static function getApTypes(){
		$sql = 'SELECT DISTINCT price_type FROM {{apartment}}';
		return Yii::app()->db->cache(param('cachingTime', 1209600), self::getDependency())->createCommand($sql)->queryColumn();
	}
	
	public static function getSquareMinMax(){
		$sql = 'SELECT MIN(square) as square_min, MAX(square) as square_max FROM {{apartment}} WHERE active = 1';
		return Yii::app()->db->cache(param('cachingTime', 1209600), self::getDependency())->createCommand($sql)->queryRow();
	}
	
	public static function getPriceMinMax($price_type  = 1){
		$sql = 'SELECT MIN(price_from_rur) as price_min, MAX(price_from_rur) as price_max FROM {{apartment}} WHERE price_type = "'.$price_type.'" AND active = 1' ;
		return Yii::app()->db->cache(param('cachingTime', 1209600), self::getDependency())->createCommand($sql)->queryRow();
	}

	public static function getModerationStatusArray($withAll = false){

		$status = array();
		if($withAll){
            $status[''] = 'Все';
        }

		$status[0] = 'Неактивно';
		$status[1] = 'Активно';
		$status[2] = 'Ожидает модерации';

		return $status;
    }

}