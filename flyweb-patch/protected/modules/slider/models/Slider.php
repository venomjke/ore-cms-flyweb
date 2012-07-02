<?php
/*
* Flyweb Dev Team
*/

class Slider extends ParentModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{slider}}';
	}

	public function behaviors(){
		return array(
		);
	}

	public function rules() {
		return array(
			array('path','file','types' => 'gif,png,jpg', 'on' => 'create'),
			array('title,descr', 'length','max' => 255)
		);
	}



	public function attributeLabels() {
		return array(
			'id' => 'Слайд',
			'path' => 'Изображение',
			'title' => 'Заголовок',
			'descr' => 'Описание'
		);
	}

	
	public function getAllImages(){
		$d = new CActiveDataProvider($this);
		return $d->getData();
	}

	public function search(){
		return new CActiveDataProvider($this);
	}
	
	public function beforeSave(){
		return parent::beforeSave();
	}

	public function afterSave(){

		return parent::afterSave();
	}

	public function beforeDelete(){
		@unlink(Yii::app()->basePath."/../uploads/slider/".$this->path);
		return parent::beforeDelete();
	}


}