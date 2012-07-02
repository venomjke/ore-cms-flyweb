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

class MainController extends ModuleUserController {
	public $modelName = 'User';

	public function filters(){
		return array(
			'accessControl',
		);
	}

	public function accessRules(){
		return array(
			array(
				'allow',
				'users'=>array('@'),
			),
			array(
				'deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex(){
		$model=$this->loadModel(Yii::app()->user->id);

		if(isset($_POST[$this->modelName])){
			if(isset($_POST['changePassword']) && $_POST['changePassword']){
				$model->scenario = 'changePass';

				$model->attributes=$_POST[$this->modelName];
				
				if($model->validate()){
					$model->setPassword();
					$model->save(false);
					Yii::app()->user->setFlash('success', tt('Your password successfully changed.'));
					$this->redirect(array('index'));
				}
			}
			else{
				$model->scenario = 'usercpanel';
				$model->attributes=$_POST[$this->modelName];
			
				if($model->save()){
					if($model->scenario == 'usercpanel'){
						Yii::app()->user->setFlash('success', tt('Your details successfully changed.'));
					}
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('index',array(
			'model'=>$this->loadModel(Yii::app()->user->id),
		));
	}

	public function getBookings($criteria){
		Yii::app()->getModule('booking');
		$model = new Booking();

		$pages = new CPagination($model->count($criteria));
		$pages->pageSize = param('module_usercpanel_bookingsPerPage', 10);
		$pages->applyLimit($criteria);

		$items = $model->with(array('time_in_value', 'time_out_value', 'apartment'))->findAll($criteria);

		return array(
			'items' => $items,
			'pages' => $pages,
		);

	}

	public function getLastBookings(){
		$criteria = new CDbCriteria;
		$criteria->order = 'date_created DESC';
		$criteria->condition = 'date_end >= :date AND user_id = :user';

		$criteria->params = array(
			':date'=> date('Y-m-d'),
			':user' => Yii::app()->user->id,
		);
		return $this->getBookings($criteria);
	}

	public function getOldBookings(){
		$criteria = new CDbCriteria;
		$criteria->order = 'date_created DESC';
		$criteria->condition = 'date_end < :date AND user_id = :user';

		$criteria->params = array(
			':date'=> date('Y-m-d'),
			':user' => Yii::app()->user->id,
		);
		return $this->getBookings($criteria);
	}

	public function countOldBookings(){
		$criteria = new CDbCriteria;
		$criteria->order = 'date_created DESC';
		$criteria->condition = 'date_end < :date AND user_id = :user';

		$criteria->params = array(
			':date'=> date('Y-m-d'),
			':user' => Yii::app()->user->id,
		);
		Yii::app()->getModule('booking');
		return Booking::model()->count($criteria);
	}

	public function actionDeclinebooking($id){
		Yii::app()->getModule('booking');
		$model = Booking::model()->with(array('time_in_value', 'time_out_value'))->findByPk($id);

		if($model===null || $model->user_id != Yii::app()->user->id){
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

	public function actionViewarchive(){
		$result = $this->getOldBookings();
		if(isset($result['items']) && $result['items']){
			$this->render('viewarchive', array(
				'model' => $result['items'],
				'pages' => $result['pages']
			));
		}
		else{
			Yii::app()->user->setFlash('error', tt('Archive of you booking is empty'));
			$this->redirect('index');
		}
	}
}

