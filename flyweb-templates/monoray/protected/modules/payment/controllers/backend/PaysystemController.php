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

class PaysystemController extends ModuleAdminController{
	public $modelName = 'Paysystem';

	public function actionIndex(){
		$systems = Paysystem::getPaysystems(true);

		if(count($systems) == 1){
			reset($systems);
			$this->redirect(array('/payment/backend/paysystem/configure', 'id' => reset($systems)->id));
		}

		if(count($systems) == 0){
			Yii::app()->user->setFlash('error', 'Не найдено ни одной подключенной платежной системы');
		}
		$this->render('index', array('systems' => $systems));
	}

	public function actionConfigure($id){
		$model = $this->loadModel($id);

		if(isset($_POST['Paysystem'])) {
			$model->payModel->attributes = $_POST[$model->payModelName];
			$model->attributes = $_POST['Paysystem'];

			if($model->payModel->validate() && $model->validate()){
				Yii::app()->user->setFlash('success', 'Настройки платежной системы успешно сохранены.');
				$model->save();
			}
        }

        $this->render('/backend/settings',array(
            'model'=>$model,
        ));
	}

	public function getCurrencyOptions(){
		return array(
			'rur'=>'RUR',
			'usd'=>'USD',
		);
	}

	public function getModeOptions(){
		return array(
			Paysystem::MODE_REAL=>Yii::t('module_payment','Real mode'),
			Paysystem::MODE_TEST=>Yii::t('module_payment','Test mode'),
		);
	}

	public function getStatusOptions(){
		return array(
			Paysystem::STATUS_ACTIVE=>Yii::t('module_payment','Active'),
			Paysystem::STATUS_INACTIVE=>Yii::t('module_payment','Inactive'),
		);
	}

}
