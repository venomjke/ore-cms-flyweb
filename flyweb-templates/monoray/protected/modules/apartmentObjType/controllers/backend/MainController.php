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
	public $modelName = 'ApartmentObjType';

	public function actionView($id){
		$this->redirect(array('admin'));
	}
	public function actionIndex(){
		$this->redirect(array('admin'));
	}

	public function actionAdmin(){
		$this->getMaxSorter();
		parent::actionAdmin();
	}

    public function actionDelete($id){

        // Не дадим удалить последний тип
        if(ApartmentObjType::model()->count() <= 1){
            if(!isset($_GET['ajax'])){
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
            Yii::app()->end();
        }

        parent::actionDelete($id);
    }
}