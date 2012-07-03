<?php
/*
* Flyweb dev team
*/

class MainController extends ModuleAdminController {

	public $modelName = 'Slider';

	public function init(){
		parent::init();
	}

	public function actionView($id){
		$this->redirect(array('admin'));
	}
	
    public function actionAdmin(){
        parent::actionAdmin();
    }

	public function actionUpdate($id){
		parent::actionUpdate($id);
	}


	public function actionCreate(){
		$model=new $this->modelName;

		if(isset($_POST[$this->modelName])){

			$model->attributes=$_POST[$this->modelName];
			$model->path=CUploadedFile::getInstance($model,'path');
			if($model->save()){
				$model->path->saveAs(Yii::app()->basePath.'/../uploads/slider/'.$model->path);
				
				$image = new Image(Yii::app()->basePath.'/../uploads/slider/'.$model->path);
				$image->resize(640,290,Image::NONE)->quality(100)->save(Yii::app()->basePath.'/../uploads/slider/'.$model->path);
				$this->redirect(array('admin'));
			}
		}
 
		$this->render('create',	array(
			'model'=>$model
		));
	}
	
}