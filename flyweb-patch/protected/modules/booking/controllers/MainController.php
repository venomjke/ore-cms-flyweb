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
	public $modelName = 'Booking';

	public function actionBookingform($isFancy = 0){
		Yii::app()->getModule('apartments');

		$this->modelName = 'Apartment';
		$apartment = $this->loadModel();
		$this->modelName = 'Booking';
		
		$isGuest = false;
		if(Yii::app()->user->isGuest){
			// If user not logined and want to login - after login use redirect to this booking form
			Yii::app()->user->setReturnUrl( Yii::app()->controller->createUrl('/booking/main/bookingform', array('id' => $apartment->id)) );
			Yii::app()->user->setState('returnedUrl', Yii::app()->controller->createUrl('/booking/main/bookingform', array('id' => $apartment->id)));
			$isGuest = true;
		}

		$booking = new Booking();
		$booking->scenario = 'bookingform';

		if(isset($_POST['Booking'])){
			$booking->attributes=$_POST['Booking'];

			$booking->apartment_id = $apartment->id;

			if($booking->validate()){
				Yii::app()->user->setFlash('success', tt('Operation successfully complete. Please check your email for further instructions.'));
				$emailText = 'Квартира: <a href="'.Yii::app()->request->hostInfo."".$apartment->getUrl().'">'.$apartment->getStrByLang('title').'</a> <br/>';
				$emailText .= 'Имя: '.$booking->username.'<br/>';
				$emailText .= 'Номер телефона: '.$booking->phone.'<br/>';
				$emailText .= 'Email: '.$booking->useremail.'<br/>';
				$emailText .= 'Комментарий: <br/>'.$booking->comment;
				mail(param("adminEmail"),"Бронирование",$emailText,"\r\nContent-type: text/html; charset=utf-8");
			}
		}

		if($isFancy){
			//Yii::app()->clientscript->scriptMap['*.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;

			$this->renderPartial('bookingform', array(
				'apartment' => $apartment,
				'model' => $booking,
				'isGuest' => $isGuest,
				'isFancy' => true,
			), false, true);
		}
		else{
			$this->render('bookingform', array(
				'apartment' => $apartment,
				'model' => $booking,
				'isGuest' => $isGuest,
				'isFancy' => false,
			));
		}
	}

	public function createUser($email, $username = '', $phone = ''){
		$model = new User;
		$model->email = $email;
		if($username){
			$model->username = $username;
		}
		if($phone){
			$model->phone = $phone;
		}
		$password = $model->randomString();
		$model->setPassword($password);
		$return = array();
		
		if($model->save()){
			$return = array(
				'email' => $model->email,
				'username' => $model->username,
				'password' => $password,
				'id' => $model->id,
			);
		}
		return $return;
	}

	public function getTimesIn(){
		$sql = 'SELECT id, title_'.Yii::app()->language.' as title FROM {{apartment_times_in}}';

		$results = Yii::app()->db->createCommand($sql)->queryAll();
		$return = array();
		if($results){
			foreach($results as $result){
				$return[$result['id']] = $result['title'];
			}
		}
		return $return;
	}

	public function getTimesOut(){
		$sql = 'SELECT id, title_'.Yii::app()->language.' as title FROM {{apartment_times_out}}';

		$results = Yii::app()->db->createCommand($sql)->queryAll();
		$return = array();
		if($results){
			foreach($results as $result){
				$return[$result['id']] = $result['title'];
			}
		}
		return $return;
	}

	public function actionMainform($isFancy = 0){

		$model = new SimpleformModel;
		$model->scenario = 'forrent';

		if(isset($_POST['SimpleformModel'])){
			$request = Yii::app()->request;
			$isForBuy = $request->getPost('isForBuy', 0);
						
			$model->attributes = $_POST['SimpleformModel'];

			if ($isForBuy) {
				$model->scenario = 'forbuy';
			}
			if($model->validate()){
				Yii::app()->user->setFlash('success', tt('Operation successfully complete. Please check your email for further instructions.'));
				$emailText = 'Тип недвижимости:'.Apartment::getNameByType($model->type).'<br/>';
				$emailText .= 'Количество комнат: '.$model->rooms.'<br/>';
				$emailText .= 'Имя: '.$model->username.'<br/>';
				$emailText .= 'Номер телефона: '.$model->phone.'<br/>';
				$emailText .= 'Email: '.$model->email.'<br/>';
				$emailText .= 'Комментарий: <br/>'.$model->comment;
				$headers = array(
				    'MIME-Version: 1.0',
				    'Content-type: text/html; charset=utf-8'
				);
				mail(param("adminEmail"),"Бронирование",$emailText,"\r\nContent-type: text/html; charset=utf-8");
				// Yii::app()->email->send('info@flywebstudio.ru',param("adminEmail"),'Бронирование',$emailText,$headers);
			}
		}
		
		$type = Apartment::getTypesWantArray();
		
		/*$type = array();
		foreach ($typeAds as $val) {
		    $type[$val] = $val;
		}*/
				
		$user = null;
		if(!Yii::app()->user->isGuest){
			$user = User::model()->findByPk(Yii::app()->user->getId());
		}

		if($isFancy){
			Yii::app()->clientscript->scriptMap['jquery.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;

			$this->renderPartial('simpleform', array(
				'model' => $model,
				'type' => $type,
				'user' => $user,
				'isFancy' => true,
			), false, true);
		}else{
			$this->render('simpleform', array(
				'model' => $model,
				'type' => $type,
				'user' => $user,
				'isFancy' => false,
			));
		}
	}

	public function getExistRooms(){
		return Apartment::getExistsRooms();
	}

}
