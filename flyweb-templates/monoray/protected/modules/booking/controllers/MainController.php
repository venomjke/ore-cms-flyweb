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

				if(Yii::app()->user->isGuest){
					$user = $this->createUser($booking->useremail, $booking->username, $booking->phone);
										
					if($user){
						$booking->user_id = $user['id'];
						$booking->password = $user['password'];
						$booking->email = $user['email'];
						$booking->username = $user['username'];
												
						$notifier = new Notifier;
						$notifier->raiseEvent('onNewUser', $booking, $user);
					}
				}  else{
					$user = null;
					$booking->user_id = Yii::app()->user->getId();
				}

				if($booking->save(false)){

					$notifier = new Notifier;
					$notifier->raiseEvent('onNewBooking', $booking);
					
					Yii::app()->user->setFlash('success', tt('Operation successfully complete. Please check your email for further instructions.'));
					$this->redirect($apartment->getUrl());
				}
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
				if (!$isForBuy) {
					$timesIn = $this->getTimesIn();
					$timesOut = $this->getTimesOut();

					$model->time_inVal = $timesIn[$model->time_in];
					$model->time_outVal = $timesOut[$model->time_out];
				}
					
				$type = Apartment::getTypesArray();
				$type = array_flip($type);
				
				$model->type = array_search($model->type, $type);
								
				if(Yii::app()->user->isGuest){
					$user = $this->createUser($model->useremail, $model->username, $model->phone);
										
					if($user){
						$model->user_id = $user['id'];
						$model->password = $user['password'];
						$model->email = $user['email'];
						$model->username = $user['username'];
					    
						$notifier = new Notifier;
						$notifier->raiseEvent('onNewUser', $model, $user);
					}
				}  else{
					//$user = null;
					$user = User::model()->findByPk(Yii::app()->user->getId());	
					$model->user_id = $user['id'];
					$model->useremail = $user['email'];
					$model->username = $user['username'];
					$model->phone = $user['phone'];
				}
				
				$notifier = new Notifier;
				
				if (!$isForBuy) {
					$notifier->raiseEvent('onNewSimpleBookingForRent', $model);
				}
				else {
					$notifier->raiseEvent('onNewSimpleBookingForBuy', $model);
				}
				
				Yii::app()->user->setFlash('success', tt('Operation successfully complete. Please check your email for further instructions.'));
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
		} else{
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
