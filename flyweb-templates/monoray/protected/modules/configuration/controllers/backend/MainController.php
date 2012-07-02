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

class MainController extends ModuleAdminController {
	public $modelName = 'ConfigurationModel';
	public $defaultAction='admin';
	
	public function actionView($id){
		$this->redirect(array('admin'));
	}

    public function actionAdmin(){

        $this->params['currentSection'] = Yii::app()->request->getQuery('section_filter', 'all');

        parent::actionAdmin();
    }

    public function actionActivate(){
        $id = intval(Yii::app()->request->getQuery('id', 0));

        if($id){
            $action = Yii::app()->request->getQuery('action');
            $model = $this->loadModel($id);

            if($model){
                $model->value = ($action == 'activate' ? 1 : 0);
                $model->update(array('value'));
            }
        }
        if(!Yii::app()->request->isAjaxRequest){
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    public function getSections($withAll = 1){
        $sql = 'SELECT section FROM {{configuration}} GROUP BY section';
        $categories = Yii::app()->db->createCommand($sql)->queryAll();

        if($withAll)
            $return['all'] = 'Все';
        foreach($categories as $category){
            $return[$category['section']] = tt($category['section']);
        }
        return $return;
    }
}
