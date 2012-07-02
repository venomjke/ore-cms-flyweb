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
	public $modelName = 'UserAds';
	public $photoUpload = false;

	
	public function init() {
		// если админ - делаем редирект на просмотр в админку
		if(Yii::app()->user->getState('isAdmin')){
			$this->redirect($this->createAbsoluteUrl('/apartments/backend/main/admin'));
		}
		parent::init();
	}
	
	public function accessRules(){
		return array(
			array(
				'allow',
                'expression' => 'param("useUserads") && !Yii::app()->user->isGuest',
			),
			array(
				'deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex(){
		$model = new $this->modelName;
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET[$this->modelName])){
			$model->attributes = $_GET[$this->modelName];
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionActivate(){

		if(isset($_GET['id']) && isset($_GET['action'])){
			$action = Yii::app()->request->getQuery('action');;
			$model = $this->loadModelUserAd($_GET['id']);
            $model->scenario = 'update_status';

			if($model){
				$model->owner_active = ($action == 'activate'?1:0);
				$model->update(array('owner_active'));
			}
		}
		if(!Yii::app()->request->isAjaxRequest){
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}


	public function actionCreate(){
		$this->modelName = 'Apartment';
		$model = new $this->modelName;

		$type = self::getReqType();

		if(isset($_POST[$this->modelName])){

			$model->attributes=$_POST[$this->modelName];

			if(param('useUseradsModeration', 1)){
				$model->active = Apartment::STATUS_MODERATION;
			} else {
				$model->active = Apartment::STATUS_ACTIVE;
			}
			$model->owner_active = Apartment::STATUS_ACTIVE;

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
			$model->owner_active = Apartment::STATUS_ACTIVE;

			if($model->save()){
				Yii::app()->user->setState('updateApartmentId', $model->id);
				$this->redirect(array('update', 'id'=>$model->id, 'show' => 'photo-gallery'));
			}
		}
        $model->type = $type;

		$this->render('create',	array(
			'model'=>$model,
			'categories' => Apartment::getCategories(NULL, $type),
		));
	}

	public function loadModelUserAd($id) {
		$model = $this->loadModel($id);
		if($model->owner_id != Yii::app()->user->id){
			throw404();
		}
		return $model;
	}

	public function actionUpdate($id){
		$model = $this->loadModelUserAd($id);

		$this->performAjaxValidation($model);

		$show = false;
		if(isset($_GET['show']) && $_GET['show']){
			$show = $_GET['show'];
		}

        if(isset($_GET['type'])){
			$type = self::getReqType();
            $model->type = $type;
        }

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];

			if(param('useUseradsModeration', 1)){
				$model->active = Apartment::STATUS_MODERATION;
			} else {
				$model->active = Apartment::STATUS_ACTIVE;
			}

			if($model->save()){
				if(!(isset($_FILES['uploader']['name'][0]) && $_FILES['uploader']['name'][0])){
					$this->redirect(array('/apartments/main/view','id'=>$model->id));				
				}
				else{
					$this->photoUpload = true;
				}
			}
		}

		$this->render('update',
			array(
				'model'=>$model,
				'categories' => Apartment::getCategories($id, $model->type),
				'show' => $show,
			)
		);
	}
	
    private static function getReqType(){
        $type = Yii::app()->getRequest()->getQuery('type');
        $existType = array_keys(Apartment::getTypesArray());
        if(!in_array($type, $existType)){
            $type = Apartment::TYPE_DEFAULT;
        }
        return $type;
    }

	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$this->loadModelUserAd($id)->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionGmap($id){
		$model = $this->loadModelUserAd($id);

		$result = MyGMap::actionGmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));
		if($result){
			return $this->renderPartial('//../modules/apartments/views/backend/_gmap', $result, true);
		}
	}

	public function actionYmap($id){
		$model = $this->loadModelUserAd($id);
		
		$result = MyYMap::init()->actionYmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));
		if($result){
			return $this->renderPartial('//../modules/apartments/views/backend/_ymap', $result, true);
		}
	}

	public function actionSavecoords($id){
		if(param('useGoogleMap', 1) || param('useYandexMap', 1)){
			$apartment = $this->loadModelUserAd($id);
			if(isset($_POST['lat']) && isset($_POST['lng'])){
				$apartment->lat = $_POST['lat'];
				$apartment->lng = $_POST['lng'];
				$apartment->save();
			}
			Yii::app()->end();
		}
	}

	public function actionView($id){
		$this->redirect(array('/apartments/main/view', 'id' => $id));
	}
}