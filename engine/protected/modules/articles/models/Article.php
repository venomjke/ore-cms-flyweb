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

class Article extends CActiveRecord {

	public $title;
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{articles}}';
	}
	
	public function rules(){
		return array(
		    array('page_title, page_body', 'required'),
		    array('page_title', 'length', 'min'=>2, 'max'=>255),
		    array('page_body', 'length', 'min'=>2),
		    array('page_title, page_body, date_updated', 'safe', 'on'=>'search'),
		);
	}
	
	public function attributeLabels(){
		return array(
			'page_title' => tt('Title / Question'),
			'page_body' => tt('Body / Answer'),
			'date_updated' => tt('Date updated'),
			'active' => tt('Status'),
		);
	}
	
	public function search(){
		
		$criteria=new CDbCriteria;
		$criteria->compare('page_title', $this->page_title, true);
		$criteria->compare('page_body', $this->page_body, true);
		
		$criteria->order = 'sorter ASC';
		return new CActiveDataProvider($this, array(
		    'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'date_updated DESC',
			),
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
		));
	}

	public function behaviors(){
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => null,
				'updateAttribute' => 'date_updated',
			),
		);
	}
	
	public function afterFind(){
		$this->title = $this->page_title;
	}

	public function beforeSave(){
		if($this->isNewRecord){
			$this->active = 1;

			$maxSorter = Yii::app()->db->createCommand()
				->select('MAX(sorter) as maxSorter')
				//->where('active=1')
				->from('{{articles}}')
				->queryScalar();
			$this->sorter = $maxSorter+1;
		}
		return parent::beforeSave();
	}

	public function getUrl(){
		return Yii::app()->createUrl('/articles/main/view', array(
			'id'=>$this->id,
			'title'=>$this->page_title,
		));
	}

	public static function getCacheDependency(){
		return new CDbCacheDependency('SELECT MAX(date_updated) FROM {{articles}}');
	}
}