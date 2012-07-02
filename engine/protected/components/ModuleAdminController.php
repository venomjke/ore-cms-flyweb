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

class ModuleAdminController extends Controller
{
	public $layout='//layouts/admin';
	public $params = array();
	public $photoUpload = false;
	public $scenario = null;
	public $with = array();
	protected $_model = null;
	
	function init(){
		$this->menuTitle = Yii::t('common', 'Operations');
		parent::init();
	}

	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.'.$this->getModule($this->id)->getName().'.views.backend');
	}

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules(){
		return array(
			array(
				'allow',
				'expression' => 'Yii::app()->user->getState("isAdmin")',
			),
			array(
				'deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id){
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate(){
		$model=new $this->modelName;
		if($this->scenario){
			$model->scenario = $this->scenario;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];
			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array_merge(
				array('model'=>$model),
				$this->params
		));
	}

	public function actionUpdate($id){
		if($this->_model === null){
			$model = $this->loadModel($id);
		}
		else{
			$model = $this->_model;
		}

		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName])){
			$model->attributes=$_POST[$this->modelName];
			if($model->save()){
				if(!(isset($_FILES['uploader']['name'][0]) && $_FILES['uploader']['name'][0])){
					$this->redirect(array('view','id'=>$model->id));
				}
				else{
					$this->photoUpload = true;
				}
			}
		}

		$this->render('update',
			array_merge(
				array('model'=>$model),
				$this->params
			)
		);
	}

	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex(){
		$dataProvider=new CActiveDataProvider($this->modelName);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin(){
		$model = new $this->modelName('search');
		if($this->scenario){
			$model->scenario = $this->scenario;
		}
		if($this->with){
			$model = $model->with($this->with);
		}
		$model->resetScope();
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET[$this->modelName])){
			$model->attributes=$_GET[$this->modelName];
		}
		$this->render('admin',
					array_merge(array('model'=>$model), $this->params)
				);
	}

	public function loadModel($id = null){
		if(!$this->_model){
			$model = new $this->modelName;
		} else {
			$model = $this->_model;
		}
		if($id !== null){
			if($this->with){
				$model = $model->resetScope()->with($this->with)->findByPk($id);
			}
			else{
				$model = $model->resetScope()->findByPk($id);
			}
		}
		if($this->scenario){
			$model->scenario = $this->scenario;
		}

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');

		$this->_model = $model;
		return $this->_model;
	}

	public function loadModelWith($with) {
		if(isset($_GET['id'])) {
			$model = new $this->modelName;
			if($this->scenario){
				$model->scenario = $this->scenario;
			}
			if($model===null){
				throw new CHttpException(404,'The requested page does not exist.');
			}
			return $model/*->resetScope()*/->with($with)->findByPk($_GET['id']);
		}
	}

	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']===$this->modelName.'-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSort(){
		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$i = 0;
			foreach ($_POST['items'] as $item) {
				$project = Project::model()->findByPk($item);
				$project->sortOrder = $i;
				$project->save();
				$i++;
			}
		}
	}

	public function actionMove(){
		if(isset($_GET['id']) && isset($_GET['direction'])){
			$direction = isset($_GET['direction']) ? $_GET['direction'] : '' ;

			$model = $this->loadModel($_GET['id']);
			$catId = Yii::app()->request->getQuery('catid', '');

			if($model && ($direction == 'up' || $direction == 'down') ){
				$addWhere = '';
				if (!empty($catId) && $catId > 0) {
				    $addWhere = ' AND reference_category_id = "'.$catId.'"';
				}	
			    
				$sorter = $model->sorter;
				
				if($direction == 'up'){
					if($sorter > 1){
						$sql = 'UPDATE '.$model->tableName().' SET sorter="'.$sorter.'" WHERE sorter < "'.($sorter).'" '.$addWhere.' ORDER BY sorter DESC LIMIT 1';
						Yii::app()->db->createCommand($sql)->execute();
						$model->sorter--;
						$model->save(false);
					}
				}
				if($direction == 'down'){
					if (!empty($catId) && $catId > 0) {
					    $maxSorter = Yii::app()->db->createCommand()
						->select('MAX(sorter) as maxSorter')
						->from($model->tableName())
						->where('reference_category_id=:catid', array(':catid'=>$catId))
						->queryScalar();
					}
					else {
					    $maxSorter = Yii::app()->db->createCommand()
						->select('MAX(sorter) as maxSorter')
						->from($model->tableName())
						->queryScalar();
					    
					}
					
					if($sorter < $maxSorter){
						$sql = 'UPDATE '.$model->tableName().' SET sorter="'.$sorter.'" WHERE sorter > "'.($sorter).'" '.$addWhere.' ORDER BY sorter ASC LIMIT 1';
						Yii::app()->db->createCommand($sql)->execute();
						$model->sorter++;
						$model->save(false);
					}
				}
			}
		}
		if(!Yii::app()->request->isAjaxRequest){
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function getMaxSorter(){
		$model = new $this->modelName;
		$maxSorter = Yii::app()->db->createCommand()
			->select('MAX(sorter) as maxSorter')
			->from($model->tableName())
			->queryScalar();
		$this->params['maxSorter'] = $maxSorter;
		return $maxSorter;
	}

	public function getMinSorter(){
		$model = new $this->modelName;
		$minSorter = Yii::app()->db->createCommand()
			->select('MIN(sorter) as maxSorter')
			->from($model->tableName())
			->queryScalar();
		$this->params['minSorter'] = $minSorter;
		return $minSorter;
	}


	public static function returnStatusHtml($data, $tableId, $onclick = 0, $ignore = 0){
		if($ignore && $data->id == $ignore){
			return '';
		}
		$url = Yii::app()->controller->createUrl("activate", array("id" => $data->id, 'action' => ($data->active==1?'deactivate':'activate') ));
		$img = CHtml::image(
					Yii::app()->request->baseUrl.'/images/'.($data->active?'':'in').'active.png',
					Yii::t('common', $data->active?'Inactive':'Active'),
					array('title' => Yii::t('common', $data->active?'Deactivate':'Activate'))
				);
		$options = array();
		if($onclick){
			$options = array(
				'onclick' => 'ajaxSetStatus(this, "'.$tableId.'"); return false;',
			);
		}
		return '<div align="center">'.CHtml::link($img,$url, $options).'</div>';
		
	}

	public static function returnControllerStatusHtml ($data, $tableId, $onclick = 0, $ignore = 0) {
		if (param('useUserads', 1)) {
			return self::returnModerationStatusHtml($data, $tableId, $onclick = 0, $ignore = 0);
		}
		return self::returnStatusHtml($data, $tableId, $onclick = 0, $ignore = 0);
	}

	public static function returnModerationStatusHtml($data, $tableId, $onclick = 0, $ignore = 0){
		$moderationStatuses = Apartment::getModerationStatusArray();

		if($data->owner_id == 1){
			//unset($moderationStatuses[2]);
		}

		$items = CJavaScript::encode($moderationStatuses);

		/*$items = '{';
		if (is_array($moderationStatuses) && count($moderationStatuses) > 1) {
			$count = count($moderationStatuses);
			$i = 1;
			foreach ($moderationStatuses as $key => $value) {
				if ($i == $count) {
					$items .= '\"'.$key.'\" : \"'.$value.'\"';
				}
				else {
					$items .= '\"'.$key.'\" : \"'.$value.'\",';
				}
				$i++;
			}
		}
		$items .= '}';*/

		$options = array(
			'onclick' => 'ajaxSetModerationStatus(this, "'.$tableId.'", "'.$data->id.'", "'.$data->owner_id.'", "'.$items.'"); return false;',
		);

		return '<div align="center" class="editable_select" id="editable_select-'.$data->id.'">'.CHtml::link(Yii::t('module_userads', $moderationStatuses[$data->active]), '#' , $options).'</div>';

	}

	public function actionActivate(){
        $field = isset($_GET['field']) ? $_GET['field'] : 'active';

		$useModuleUserAds = false;
		if (param('useUserads', 1) && Yii::app()->request->getParam('id') && (Yii::app()->request->getParam('value') != null)) {
			$useModuleUserAds = true;
			$this->scenario = 'update_status';
			$action = Yii::app()->request->getParam('value', null);
			$id = Yii::app()->request->getParam('id', null);
			$availableStatuses = Apartment::getModerationStatusArray();

			if (!array_key_exists($action, $availableStatuses)) {
				$action = 0;
			}
		}
		else {
			$action = $_GET['action'];
			$id = $_GET['id'];
		}

		if(!(!$id && $action === null)){
			$model = $this->loadModel($id);

			if($this->scenario){
				$model->scenario = $this->scenario;
			}

			if($model){
				if ($useModuleUserAds) {
					$model->$field = $action;
				}
				else {
					$model->$field = ($action == 'activate'?1:0);
				}
				$model->update(array($field));
			}
		}

		if(!Yii::app()->request->isAjaxRequest){
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		elseif ($useModuleUserAds)  {
			echo CHtml::link($availableStatuses[$action]);
		}
	}

	/*public function actionActivate(){
        $field = isset($_GET['field']) ? $_GET['field'] : 'active';

		if(isset($_GET['id']) && isset($_GET['action'])){
			$action = $_GET['action'];
			$model = $this->loadModel($_GET['id']);
			if($this->scenario){
				$model->scenario = $this->scenario;
			}else{
                $model->scenario = 'changeStatus';
            }
			if($model){
				$model->$field = ($action == 'activate'?1:0);
				$model->update(array($field));
			}
		}
		if(!Yii::app()->request->isAjaxRequest){
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}*/

    public function actionItemsSelected(){
        $idsSelected = Yii::app()->request->getPost('itemsSelected');

        $work = Yii::app()->request->getPost('workWithItemsSelected');

        if($idsSelected && is_array($idsSelected) && $work){
            $idsSelected = array_map('intval', $idsSelected);

            foreach($idsSelected as $id){
                $model = $this->loadModel($id);
                $model->scenario = 'changeStatus';

                if($work == 'delete'){
                    $model->delete();
                }elseif($work == 'activate') {
                    $model->active = 1;
                    $model->update('active');
                }elseif($work == 'deactivate') {
                    $model->active = 0;
                    $model->update('active');
                }
            }
        }

        if(!Yii::app()->request->isAjaxRequest){
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

}