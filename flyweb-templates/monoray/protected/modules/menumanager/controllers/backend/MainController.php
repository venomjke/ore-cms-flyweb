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
	public $modelName = 'Menu';

	public function actionView($id){
		if($id == 2){
			$this->redirect(array('/news/main/index'));
		}
		if($id == 3){
			$this->redirect(array('/specialoffers/main/index'));
		}
		if($id == 4){
			$this->redirect(array('/articles/backend/main/index'));
		}
		if($id == 5){
			$this->redirect(array('/sitemap/main/index'));
		}


		parent::actionView($id);
	}

	public function actionIndex(){
		$this->redirect(array('admin'));
	}

	public function actionCreate(){
		$model = new Menu();
		
		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];

			$model->scenario = 'link_'.$model->type;

			// DELETE subitems

			if($model->save()){
				$this->redirect(array('admin'));
			}
		}
		$this->render('create', array('model'=>$model));
	}

	public function actionDelete($id){
		if($id < 5){
			Yii::app()->user->setFlash('error', 'Данный пункт меню является системным и его нельзя удалить. Но вы можете отключить его в столбце "Включено".');
			$this->redirect('admin');
		}
		if (Yii::app()->cache->get('menu'))
			Yii::app()->cache->delete('menu');
		
		parent::actionDelete($id);
	}

	public function actionUpdate($id){
		$model = $this->loadModel($id);

		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];
			$model->scenario = 'link_'.$model->type;

			if($model->special){
				$model->scenario = 'special';
			}
			
			if($model->save()){
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',
			array('model'=>$model)
		);
	}

	public function actionAdmin(){
		$this->getMaxSorter();
		$this->getMinSorter();
		$this->scenario = 'create';
		parent::actionAdmin();
	}

}
