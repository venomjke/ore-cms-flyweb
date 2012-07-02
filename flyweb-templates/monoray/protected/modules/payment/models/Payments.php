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

class Payments extends CActiveRecord {
	const STATUS_WAITPAYMENT=1;
	const STATUS_PAYMENTCOMPLETE=2;
	const STATUS_DECLINED=3;
	const STATUS_WAITOFFLINE = 4;
    public $paysystem_name;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{payments}}';
	}

	public function rules() {
		return array(
			array('sum,status', 'required'),
			array('sum,status,paysystem_id', 'numerical'),
			array('id, sum, status, paysystem_name, paysystem_id', 'safe', 'on' => 'search'),
		);
	}

	public function relations() {
		return array(
			'order' => array(self::BELONGS_TO, 'Booking', 'order_id'),
            'paysystem' => array(self::BELONGS_TO, 'Paysystem', 'paysystem_id')
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('module_payment', 'Payment #'),
			'sum' => Yii::t('module_payment', 'Amount'),
			'status' => Yii::t('module_payment', 'Status'),
			'date_created' => Yii::t('module_payment', 'Payment date'),
			'order' => Yii::t('module_payment', 'Booking #'),
            'paysystem_name' => 'Способ оплаты'
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id, true);
		$criteria->compare('t.sum', $this->sum, true);
		$criteria->compare('t.status', $this->status, true);
		$criteria->compare('paysystem.name', $this->paysystem_name, true);

		$criteria->with = array('order','paysystem');

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.date_created DESC',
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

	public function getStatuses() {
		return array(
			'' => '',
			Payments::STATUS_WAITPAYMENT => tt('Wait for payment', 'payment'),
			Payments::STATUS_PAYMENTCOMPLETE => tt('Payment complete', 'payment'),
			Payments::STATUS_DECLINED => tt('Payment declined', 'payment'),
			Payments::STATUS_WAITOFFLINE => 'Ожидает подтверждения о получении',
		);
	}

	public function returnStatusHtml() {
		$return = '';
		switch ($this->status) {
			case self::STATUS_WAITPAYMENT:
				$return = tt('Wait for payment', 'payment');
				break;
			case self::STATUS_PAYMENTCOMPLETE:
				$return = tt('Payment complete', 'payment');
				break;
			case self::STATUS_DECLINED:
				$return = tt('Payment declined', 'payment');
				break;
			case self::STATUS_WAITOFFLINE:
				$return = 'Ожидает подтверждения о получении';
				break;
		}
		return $return;
	}

}