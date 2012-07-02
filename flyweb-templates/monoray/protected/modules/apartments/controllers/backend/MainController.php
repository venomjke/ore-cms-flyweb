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

class MainController extends ModuleAdminController {

	public $modelName = 'Apartment';

	public function init(){
		if(issetModule('metrostations')){
			Yii::import('application.modules.metrostations.models.MetroStation');
		}
		parent::init();
	}

	public function actionView($id) {
		//$this->layout='//layouts/inner';
		$this->render('view', array(
			'model' =>  $this->loadModelWith(array('windowTo', 'objType', 'city')),
		));
	}

    public function actionAdmin(){
        /*if(isset($_GET['Apartment']['type_filter'])){
            $this->params['currentType'] = intval($_GET['Apartment']['type_filter']);
        }else{
            $this->params['currentType'] = 0;
        }*/

        $countNewsProduct = NewsProduct::getCountNoShow();
        if($countNewsProduct > 0){
            Yii::app()->user->setFlash('mesNewNews', Yii::t('common', 'There are new product news') . ': '
                . CHtml::link(Yii::t('common', '{n} news', $countNewsProduct), array('/news/backend/main/product')));
        }

		$this->with = array('user');

        /*if(isset($_GET['Apartment']['metro_filter'])){
            $this->params['currentStation'] = intval($_GET['Apartment']['metro_filter']);
        }else{
            $this->params['currentStation'] = 0;
        }*/
		$this->getMaxSorter();
        parent::actionAdmin();
    }

	public function actionUpdate($id){
		$this->scenario = 'savecat';
        $this->_model = $this->loadModel($id);
        if(isset($_GET['type'])){
            $type = self::getReqType();

            $this->_model->type = $type;
        }

		$this->params['show'] = false;
		if(isset($_GET['show']) && $_GET['show']){
			$this->params['show'] = $_GET['show'];
		}

		$this->params['categories'] = array();
		if(!isset($_POST['Apartment']) || (isset($_FILES['uploader']['name'][0]) && $_FILES['uploader']['name'][0]) ){
			$this->params['categories'] = Apartment::getCategories($_GET['id'], $this->_model->type);
		}

		parent::actionUpdate($id);
	}

    private static function getReqType(){
        $type = Yii::app()->getRequest()->getQuery('type');
        $existType = array_keys(Apartment::getTypesArray());
        if(!in_array($type, $existType)){
            $type = Apartment::TYPE_DEFAULT;
        }
        return $type;
    }

	public function actionCreate(){
		$model=new $this->modelName;

        $type = self::getReqType();

		if(isset($_POST[$this->modelName])){

			$model->attributes=$_POST[$this->modelName];

			$coords = array();
			if(($model->address_ru && $model->city) && (param('useGoogleMap', 1) || param('useYandexMap', 1))){
				$city = null;
				if($model->city_id){
					$city = ApartmentCity::model()->findByPk($model->city_id);
					if($city){
						$city = $city->name;
					} else {
						$city = null;
					}
				}

				$coords = Geocoding::getCoordsByAddress($model->address_ru, $city);
				if(isset($coords['lat']) && isset($coords['lng'])){
					$model->lat = $coords['lat'];
					$model->lng = $coords['lng'];
				}
			}

			$model->scenario = 'savecat';
			if($model->save()){
				$this->redirect(array('update', 'id'=>$model->id, 'show' => 'photo-gallery'));
			}
		}
        $model->type = $type;

		$this->render('create',	array(
			'model'=>$model,
			'categories' => Apartment::getCategories(NULL, $type),
		));
	}
	
	public function getWindowTo(){
		$sql = 'SELECT id, title_'.Yii::app()->language.' as title FROM {{apartment_window_to}}';
		$results = Yii::app()->db->createCommand($sql)->queryAll();
		$return = array();
		$return[0] = '';
		if($results){
			foreach($results as $result){
				$return[$result['id']] = $result['title'];
			}
		}
		return $return;
	}

	/*public function getMetro(){
		$sql = 'SELECT id, name_'.Yii::app()->language.' as title FROM {{apartment_metro_station}}';
		$results = Yii::app()->db->createCommand($sql)->queryAll();
		$return = array();
		$return[0] = '';
		if($results){
			foreach($results as $result){
				$return[$result['id']] = $result['title'];
			}
		}
		return $return;
	}*/

	public function actionSavecoords($id){
		if(param('useGoogleMap', 1) || param('useYandexMap', 1)){
			$apartment = $this->loadModel($id);
			if(isset($_POST['lat']) && isset($_POST['lng'])){
				$apartment->lat = $_POST['lat'];
				$apartment->lng = $_POST['lng'];
				$apartment->save();
			}
			Yii::app()->end();
		}
	}

	public function actionGmap($id, $model = null){
		if($model === null){
			$model = $this->loadModel($id);
		}
		$result = MyGMap::actionGmap($id, $model, $this->renderPartial('_marker', array('model' => $model), true));

		if($result){
			return $this->renderPartial('_gmap', $result, true);
		}
		return '';
	}
	
	public function actionYmap($id, $model = null){
		
		if($model === null){
			$model = $this->loadModel($id);
		}
		
		$result = MyYMap::init()->actionYmap($id, $model, $this->renderPartial('_marker', array('model' => $model), true));

		if($result){
			//return $this->renderPartial('backend/_ymap', $result, true);
		}
		return '';
	}
}