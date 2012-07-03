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

class News extends CActiveRecord {

	public $title;
	public $body;
	public $dateCreated;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{news}}';
	}

	public function rules() {
		return array(
			array('title, body', 'required'),
			array('title', 'length', 'max' => 128, 'message' => 'Maximum lenght of title is 128 symbols.'),
			array('title, body', 'safe', 'on' => 'search'),
		);
	}

	public function relations() {

		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'title' => tt('News title', 'news'),
			'body' => tt('News body', 'news'),
			'date_created' => tt('Creation date', 'news'),
			'dateCreated' => tt('Creation date', 'news'),
		);
	}

	public function getUrl() {
		return Yii::app()->createUrl('/news/main/view', array(
			'id' => $this->id,
			'title' => $this->title,
		));
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('title', $this->title, true);
		$criteria->compare('body', $this->body, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'date_created DESC',
			),
			'pagination' => array(
				'pageSize' => param('adminPaginationPageSize', 20),
			),
		));
	}

	public function behaviors(){
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'date_created',
				'updateAttribute' => 'date_updated',
			),
		);
	}
	
	protected function afterFind() {
		$dateFormat = param('newsModule_dateFormat', 0) ? param('newsModule_dateFormat') : param('dateFormat', 'd.m.Y H:i:s');
		$this->dateCreated = date($dateFormat, strtotime($this->date_created));

		return parent::afterFind();
	}

	public function getAllWithPagination($inCriteria = null){
		if($inCriteria === null){
			$criteria = new CDbCriteria;
			$criteria->order = 'date_created DESC';
		} else {
			$criteria = $inCriteria;
		}

		$pages = new CPagination($this->count($criteria));
		$pages->pageSize = param('module_news_itemsPerPage', 4);
		$pages->applyLimit($criteria);

		$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{news}}');

		$items = $this->cache(param('cachingTime', 1209600), $dependency)->findAll($criteria);

		return array(
			'items' => $items,
			'pages' => $pages,
		);
	}

}