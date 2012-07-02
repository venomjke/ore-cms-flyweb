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

class MainController extends ModuleAdminController{
	public $modelName = 'User';
	public $scenario = 'backend';

	public function actionCreate(){
		$model=new $this->modelName;
		if($this->scenario){
			$model->scenario = $this->scenario;
		}

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];
			if($model->validate()){
				$model->setPassword();
				$model->save(false);
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array_merge(
				array('model'=>$model),
				$this->params
		));
	}

	public function actionAdmin(){
		$this->with = array('bookingsCount');
		parent::actionAdmin();
	}

	public function getBookings($criteria){
		Yii::app()->getModule('booking');
		$model = new Booking();

		$pages = new CPagination($model->count($criteria));
		$pages->pageSize = param('module_users_bookingsPerPage', 10);
		$pages->applyLimit($criteria);

		$items = $model->with(array('time_in_value', 'time_out_value'))->findAll($criteria);
		return array(
			'items' => $items,
			'pages' => $pages,
		);
	}

	public function actionUpdate($id){
		$this->scenario = 'update';
		parent::actionUpdate($id);
	}

	public function actionBookings($id){
		$criteria = new CDbCriteria;
		$criteria->order = 'date_created DESC';
		$criteria->condition = 'user_id = :user';
		$criteria->params = array(
			':user' => $id,
		);
		
		$user = $this->loadModel($id);

		$result = $this->getBookings($criteria);
		$this->render('bookings', array(
			'model' => $result['items'],
			'pages' => $result['pages'],
			'user' => $user,
		));
	}

	public function actionDeclinebooking($id){
		Yii::app()->getModule('booking');
		$model = Booking::model()->with(array('time_in_value', 'time_out_value'))->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}

		if($model->status == Booking::STATUS_NEW || $model->status == Booking::STATUS_WAITPAYMENT){
			$model->status = Booking::STATUS_DECLINED;
			$model->save(false);
			$this->renderPartial('_booking', array(
				'model' => $model,
			));
		}
	}
}