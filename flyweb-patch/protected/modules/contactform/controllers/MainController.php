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
	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.contactform.views');
	}

	public function actions() {
		return array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
		);
	}

	public function actionIndex(){
		$model = new ContactForm;
		if(isset($_POST['ContactForm'])){
			$model->attributes = $_POST['ContactForm'];
			if($model->validate()){
				$headers = "From: {$model->email}rnReply-To: {$model->email}";
				mail(param("adminEmail"),"Контакты",$model->body,$headers);
				Yii::app()->user->setFlash('contact','Спасибо за отправленное письмо');
				$this->refresh();
			}
		}
		$this->render('contactform');
	}
}
9533405676