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

class Comment extends CActiveRecord {
	const STATUS_PENDING=0;
	const STATUS_APPROVED=1;
	public $verifyCode;
	public $dateCreated;
	public $username;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{apartment_comments}}';
	}

	public function rules() {
		return array(
			array('verifyCode', (Yii::app()->user->isGuest) ? 'required' : 'safe', 'on' => 'insert'),
			array('verifyCode', 'captcha', 'on' => 'insert', 'allowEmpty'=>!Yii::app()->user->isGuest),
			array('body, name, email', 'required'),
			array('name, email', 'length', 'max' => 128),
			array('email', 'email'),
			array('rating', 'safe'),
		);
	}

	public function relations() {
		Yii::import('application.modules.apartments.models.Apartment');
		return array(
			'apartment' => array(self::BELONGS_TO, 'Apartment', 'apartment_id'),
		);
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
	
	public function attributeLabels() {
		return array(
			'id' => 'Id',
			'body' => Yii::t('module_comments', 'Comment'),
			'rating' => Yii::t('module_comments', 'Rate'),
			'active' => Yii::t('module_comments', 'Status'),
			'date_created' => Yii::t('module_comments', 'Creation date'),
			'name' => Yii::t('module_comments', 'Name'),
			'email' => Yii::t('module_comments', 'Email'),
			'apartment_id' => Yii::t('module_comments', 'Apartment_id'),
			'verifyCode' => 'Введите код с картинки',
		);
	}

	private function _updateRating(){
		$sql = 'SELECT AVG(rating) FROM {{apartment_comments}}
			WHERE apartment_id = "'.$this->apartment_id.'" AND active = "'.Comment::STATUS_APPROVED.'" AND rating > -1';
		$rating = intval(Yii::app()->db->createCommand($sql)->queryScalar());

		$sql = 'UPDATE {{apartment}} SET rating = "'.$rating.'" WHERE id = "'.$this->apartment_id.'"';
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function search(){
		$criteria = new CDbCriteria(array('order'=>'id DESC',
			    ));

		$criteria->compare('name',$this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
		));
	}

	protected function beforeSave() {
		if ($this->isNewRecord) {
			if (param('commentNeedApproval', 1)){
				$this->active = Comment::STATUS_PENDING;
			} else {
				$this->active = Comment::STATUS_APPROVED;
			}
			$notifier = new Notifier;
			$notifier->raiseEvent('onNewComment', $this);
		}
		return parent::beforeSave();
	}

	protected function afterSave(){
		if ($this->active == Comment::STATUS_APPROVED){
			$this->_updateRating();
		}
		return parent::afterSave();
	}

	public function afterDelete(){
		if ($this->active == Comment::STATUS_APPROVED){
			$this->_updateRating();
		}
		return parent::afterDelete();
	}
	
	protected function afterFind() {
		$dateFormat = param('commentModule_dateFormat', 0) ? param('commentModule_dateFormat') : param('dateFormat', 'd.m.Y H:i:s');
		$this->dateCreated = date($dateFormat, strtotime($this->date_created));

		return parent::afterFind();
	}

    public static function getUserEmailLink($data) {
        return "<a href='mailto:".$data->email."'>".$data->name."</a>";
    }

}