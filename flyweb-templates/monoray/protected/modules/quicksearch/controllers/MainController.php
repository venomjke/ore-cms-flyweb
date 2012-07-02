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

class MainController extends ModuleUserController{

	public $roomsCount;
	public $roomsCountMin;
	public $roomsCountMax;
	public $floorCount;
	public $floorCountMin;
	public $floorCountMax;
	public $squareCount;
	public $squareCountMin;
	public $squareCountMax;
	public $price;
	public $priceSlider = array();
	public $metroStations;
	public $selectedStations;
	public $selectedCity;
	public $apType;
	public $objType;


	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition = 'active = 1';

        if(isset($_POST['is_ajax'])){
            $this->renderPartial('index', array(
                'criteria' => $criteria,
                'apCount' => null,
            ), false, true);
        }else{
            $this->render('index', array(
                'criteria' => $criteria,
                'apCount' => null,
            ));
        }
	}

	public function getExistRooms(){
		return Apartment::getExistsRooms();
	}

	public function actionMainsearch() {
		$criteria = new CDbCriteria;
		$criteria->condition = 'active=1';

		// rooms
		if (issetModule('selecttoslider') && param('useRoomSlider') == 1) {
			$roomsMin = Yii::app()->request->getParam('roomsMin');
			$roomsMax = Yii::app()->request->getParam('roomsMax');
			
			if ($roomsMin || $roomsMax) {
				$criteria->addCondition('num_of_rooms >= :roomsMin AND num_of_rooms <= :roomsMax');
				$criteria->params[':roomsMin'] = $roomsMin;
				$criteria->params[':roomsMax'] = $roomsMax;
				
				$this->roomsCountMin = $roomsMin;
				$this->roomsCountMax = $roomsMax;
			}					
		}
		else {
			$rooms = Yii::app()->request->getParam('rooms');
			if($rooms){
				if($rooms == 4){
					$criteria->addCondition('num_of_rooms >= :rooms');
				}
				else{
					$criteria->addCondition('num_of_rooms = :rooms');
				}
				$criteria->params[':rooms'] = $rooms;

				$this->roomsCount = $rooms;
			}
		}
		
		// floor
		if (issetModule('selecttoslider') && param('useFloorSlider') == 1) {
			$floorMin = Yii::app()->request->getParam('floorMin');
			$floorMax = Yii::app()->request->getParam('floorMax');
			
			if ($floorMin || $floorMax) {
				$criteria->addCondition('floor >= :floorMin AND floor <= :floorMax');
				$criteria->params[':floorMin'] = $floorMin;
				$criteria->params[':floorMax'] = $floorMax;
				
				$this->floorCountMin = $floorMin;
				$this->floorCountMax = $floorMax;
			}					
		}
		else {
			$floor = Yii::app()->request->getParam('floor');
			if($floor){
				$criteria->addCondition('floor = :floor');
				$criteria->params[':floor'] = $floor;

				$this->floorCount = $floor;
			}
		}
		
		// square
		if (issetModule('selecttoslider') && param('useSquareSlider') == 1) {
			$squareMin = Yii::app()->request->getParam('squareMin');
			$squareMax = Yii::app()->request->getParam('squareMax');
			
			if ($squareMin || $squareMax) {
				$criteria->addCondition('square >= :squareMin AND square <= :squareMax');
				$criteria->params[':squareMin'] = $squareMin;
				$criteria->params[':squareMax'] = $squareMax;
				
				$this->squareCountMin = $squareMin;
				$this->squareCountMax = $squareMax;
			}					
		}
		else {
			$square = Yii::app()->request->getParam('square');
			if($square){
				$criteria->addCondition('square <= :square');
				$criteria->params[':square'] = $square;

				$this->squareCount = $square;
			}
		}
		

		if (issetModule('metrostations')) {
			// metro
			$metro = Yii::app()->request->getParam('metro-select');
			if ($metro) {
				$apartmentIds = $this->getApIds($metro);
				$this->selectedStations = $metro;
				$criteria->addInCondition('t.id', $apartmentIds);
			}
		}

	    // city
		$city = Yii::app()->request->getParam('city');
		if ($city) {
			$this->selectedCity = $city;
			$criteria->addInCondition('t.city_id', $city);
		}
		
		$this->objType = Yii::app()->request->getParam('objType');
		if ($this->objType) {			
			$criteria->addCondition('obj_type_id = :objType');
			$criteria->params[':objType'] = $this->objType;
		}

		// type
		$this->apType = Yii::app()->request->getParam('apType');
		if($this->apType){
			$criteria->addCondition('price_type = :apType');
			$criteria->params[':apType'] = $this->apType;
		}
		
		// price
		if (issetModule('selecttoslider') && param('usePriceSlider') == 1) {
			$priceMin = Yii::app()->request->getParam("price_{$this->apType}_Min");
			$priceMax = Yii::app()->request->getParam("price_{$this->apType}_Max");
			
			if ($priceMin || $priceMax) {
				$criteria->addCondition('price_from_rur >= :priceMin AND price_from_rur <= :priceMax');
				$criteria->params[':priceMin'] = $priceMin;
				$criteria->params[':priceMax'] = $priceMax;

				$this->priceSlider["min_{$this->apType}"] = $priceMin;
				$this->priceSlider["max_{$this->apType}"] = $priceMax;
			}					
		}
		else {
			$price = Yii::app()->request->getParam('price');
			if($price){
				$criteria->addCondition('price_from_rur <= :price');
				$criteria->params[':price'] = $price;

				$this->price = $price;
			}
		}
		
		// Поиск по справочникам - клик в просмотре профиля анкеты
		if (param('useReferenceLinkInView')) {
			if (Yii::app()->request->getQuery('serviceId', false)) {
				$serviceId = Yii::app()->request->getQuery('serviceId', false);
				if ($serviceId) {
					$serviceIdArray = explode('-', $serviceId);
					if (is_array($serviceIdArray) && count($serviceIdArray) > 0) {
						$value = (int) $serviceIdArray[0];

						$sql = 'SELECT DISTINCT apartment_id FROM {{apartment_reference}} WHERE reference_value_id = '.$value;
						$apartmentIds = Yii::app()->db->cache(param('cachingTime', 1209600), Apartment::getDependency())->createCommand($sql)->queryColumn();
						//$apartmentIds = Yii::app()->db->createCommand($sql)->queryColumn();
						$criteria->addInCondition('t.id', $apartmentIds);
					}
				}
			}
		}
		
		// find count
		$apCount = Apartment::model()->count($criteria);

		if (issetModule('metrostations')) {
			$this->metroStations = MetroStation::getActiveStations();
		}

        if(isset($_POST['is_ajax'])){
            $this->renderPartial('index', array(
                'criteria' => $criteria,
                'apCount' => $apCount,
            ), false, true);
        }else{
            $this->render('index', array(
                'criteria' => $criteria,
                'apCount' => $apCount,
            ));
        }
	}
	
	public function getApIds($ids){
		return MetroStation::getApartmentsIds($ids);
	}

}