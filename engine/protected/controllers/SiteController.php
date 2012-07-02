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

class SiteController extends Controller {

	public $metroStations;
	public $cityActive;

	public function actions() {
		return array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
		);
	}

	public function accessRules(){
        return array(
            array('allow',
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('viewreferences'),
                'expression' => 'Yii::app()->user->getState("isAdmin")',
            ),
        );
    }

	public function init(){
		$this->metroStations = SearchForm::stationsInit();
        $this->cityActive = SearchForm::cityInit();
		parent::init();
	}
	public function actionIndex() {
		//$dependency = new CDbCacheDependency('SELECT date_updated FROM {{menu}} WHERE id = "1"');
		$page = Menu::model()->/*cache(param('cachingTime', 1209600), $dependency)->*/findByPk(1);
		
        if(isset($_POST['is_ajax'])){
            $this->renderPartial('index', array('page' => $page), false, true);
        }else{
            $this->render('index', array('page' => $page));
        }
    }

	public function actionError() {
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionLogin() {
		$model = new LoginForm;

		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login()){
				if(Yii::app()->user->getState('isAdmin')){
                    NewsProduct::getProductNews();
					$this->redirect(array('/apartments/backend/main/admin'));
					Yii::app()->end();
				}

				if(Yii::app()->user->isGuest){
					$this->redirect(Yii::app()->user->returnUrl);
				}
				else{
					if(!Yii::app()->user->getState('returnedUrl')){
						$this->redirect(array('/usercpanel/main/index'));
					}
					else{
						$this->redirect(Yii::app()->user->getState('returnedUrl'));
					}
				}
			}
		}
		$this->render('login', array('model' => $model));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		
		if (isset(Yii::app()->request->cookies['itemsSelectedImport'])) 
		    unset(Yii::app()->request->cookies['itemsSelectedImport']);
		
		if (isset(Yii::app()->request->cookies['itemsSelectedExport']))
		    unset(Yii::app()->request->cookies['itemsSelectedExport']);
		
		if (isset(Yii::app()->session['importAds']))
			unset(Yii::app()->session['importAds']);
		
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionViewreferences(){
		$this->layout = '//layouts/admin';
		$this->render('view_reference');
	}
	
	public function actionRecover() {
		$modelRecover = new RecoverForm;
		
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'recover-form') {
			echo CActiveForm::validate($modelRecover);
			Yii::app()->end();
		}

		if (isset($_POST['RecoverForm'])) {
			$modelRecover->attributes = $_POST['RecoverForm'];
			
			if ($modelRecover->validate()){
				//$model = new User;
				//$model->attributes = $_POST['RecoverForm'];
				$model = User::model()->findByAttributes(array('email' => $modelRecover->email));
								
				if($model !== null ){
					$password = $model->randomString();
					
					// set salt pass
					$model->setPassword($password);
					// set new password in db
					$model->update(array('password', 'salt'));			
					
					$model->password = $password;

					// send email
					$notifier = new Notifier;
					$notifier->raiseEvent('onRecoveryPassword', $model, $model->id, $password);

					showMessage(Yii::t('common', 'Recover password'), Yii::t('common', 'New password is saved and send to {email}.', array('{email}' => $modelRecover->email)));
				} else {
					showMessage(Yii::t('common', 'Recover password'), Yii::t('common', 'User does not exist'));
				}
			}
		}
		$this->render('recover', array('model' => $modelRecover));
	}

	public function actionRegister() {
		if (Yii::app()->user->isGuest) {
			$model = new User('register');

			if(isset($_POST['User'])) {
				$model->attributes = $_POST['User'];
				if($model->validate()) {
					$activateKey = $this->generateActivateKey();
					$user = $this->createUser($model->email, $model->username, $activateKey);

					if ($user) {
						$model->id = $user['id'];
						$model->password = $user['password'];
						$model->email = $user['email'];
						$model->username = $user['username'];
						$model->activatekey = $user['activateKey'];
						$model->activateLink = $user['activateLink'];

						$notifier = new Notifier;
						$notifier->raiseEvent('onRegistrationUser', $model, $model->id);
						showMessage(Yii::t('common', 'Registration'), Yii::t('common', 'You were successfully registered. The letter for account activation has been sent on {useremail}', array('{useremail}' => $user['email'])));
					}
					else {
						showMessage(Yii::t('common', 'Registration'), Yii::t('common', 'Error. Repeat attempt later'));
					}
				}
			}
			$this->render('register', array('model'=>$model));
		} else {
			$this->redirect('index');
		}
	}

	public function generateActivateKey() {
		return md5(uniqid());
	}

	public function createUser($email, $username, $activateKey = ''){
		$model = new User;
		$model->email = $email;
		$model->username = $username;
		if ($activateKey) {
			$model->activatekey = $activateKey;
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
				'activateKey' => $activateKey,
				'activateLink' => Yii::app()->createAbsoluteUrl('/site/activation', array('key' => $activateKey))
			);
		}
		return $return;
	}

	public function actionActivation() {
		$key = Yii::app()->request->getParam('key');
		if ($key) {
			$user = User::model()->find('activatekey = :activatekey',
										array(':activatekey' => $key));

			if(!empty($user)) {
				if($user->active == '1') {
					showMessage(Yii::t('common', 'Activate account' ), Yii::t('common', 'Your status account already is active'));
				}
				else {
					$user->active = '1';
					//$user->activatekey = '';
					//$user->save();
					$user->update(array('active'));
					showMessage(Yii::t('common', 'Activate account' ), Yii::t('common', 'Your account successfully activated'));
				}
			} else {
				throw new CHttpException(403, Yii::t('common', 'User not exists'));
			}
		}
		else
			$this->redirect('index');
	}

}