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

class Booking extends CActiveRecord {
	
	public $username;
	public $comment;
	public $useremail;
	public $useremailSearch;
	public $tostatus;
	public $apartment_id;
	public $phone;
	public $email;
	public $dateCreated;
	public $password;

	const STATUS_NEW=0;
	const STATUS_WAITPAYMENT=1;
	const STATUS_PAYMENTCOMPLETE=2;
	const STATUS_DECLINED=3;
	const STATUS_WAITOFFLINE = 4;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{booking}}';
	}

	public static function getYiiDateFormat() {
		$return = 'MM/dd/yyyy';
		if (Yii::app()->language == 'ru') {
			$return = 'dd.MM.yyyy';
		}
		return $return;
	}

	public function rules() {
		return array(
			array('date_start, date_end, time_in, time_out, ' . (Yii::app()->user->isGuest ? 'useremail, phone, username' : ''), 'required', 'on' => 'bookingform'),
			array('status, time_in, time_out, sum_rur', 'numerical', 'integerOnly' => true),
			array('useremail, username, comment', 'safe'),
			array('useremail', 'email'),
			array('date_start, date_end', 'date', 'format' => self::getYiiDateFormat(), 'on' => 'bookingform'),
			array('date_start, date_end', 'myDateValidator', 'on' => 'bookingform'),
			array('useremail', 'myUserEmailValidator', 'on' => 'bookingform'),
			array('useremail, username', 'length', 'max' => 128),
			//array('phone', 'required', 'on' => 'bookingform'),
			array('date_start, date_end, date_created, status, useremailSearch, apartment_id, id', 'safe', 'on' => 'search'),

			array('sum_rur', 'mySumValidator', 'on' => 'view'),
		);
	}

	public function mySumValidator($param){
		if($this->status == Booking::STATUS_NEW){
			if($param == 'sum_rur'){
				if($this->sum_rur != intval($this->sum_rur) || !$this->sum_rur){
					$this->addError('sum_rur', Yii::t('module_booking', 'Incorrect booking price.'));
				}
			}
		}
	}

	public function myUserEmailValidator() {
		if (Yii::app()->user->isGuest) {
			$model = User::model()->findByAttributes(array('email' => $this->useremail));
			if ($model) {
				$this->addError('useremail',
					Yii::t('module_booking', 'User with such e-mail already registered. Please <a title="Login" href="{n}">login</a> and try again.',
						Yii::app()->createUrl('/site/login')));
			}
		}
	}

	public function myDateValidator($param) {
		$dateStart = CDateTimeParser::parse($this->date_start, self::getYiiDateFormat()); // format to unix timestamp
		$dateEnd = CDateTimeParser::parse($this->date_end, self::getYiiDateFormat()); // format to unix timestamp

		if ($param == 'date_start' && $dateStart < CDateTimeParser::parse(date('Y-m-d'), 'yyyy-MM-dd')) {
			$this->addError('date_start', tt('Wrong check-in date', 'booking'));
		}
		if ($param == 'date_end' && $dateEnd <= $dateStart) {
			$this->addError('date_end', tt('Wrong check-out date', 'booking'));
		}
	}

	public function relations() {
		Yii::import('application.modules.apartments.models.Apartment');
		return array(
			//'apartment' => array(self::HAS_ONE, 'Apartment', '', 'on'=>'apartment_id = apartment.id'),
			'apartment' => array(self::BELONGS_TO, 'Apartment', 'apartment_id'),
			'payments' => array(self::HAS_MANY, 'Payment', 'order_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'time_in_value' => array(self::HAS_ONE, 'TimesIn', '', 'on'=>'t.time_in = time_in_value.id'),
			'time_out_value' => array(self::HAS_ONE, 'TimesOut', '', 'on'=>'t.time_out = time_out_value.id'),
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
			'date_start' => tt('Check-in date', 'booking'),
			'date_end' => tt('Check-out date', 'booking'),
			'email' => Yii::t('common', 'E-mail'),
			'time_in' => tt('Check-in time', 'booking'),
			'time_out' => tt('Check-out time', 'booking'),
			'comment' => tt('Comment', 'booking'),
			'username' => tt('Your name', 'booking'),
			'status' => tt('Status', 'booking'),
			'useremail' => Yii::t('common', 'E-mail'),
			'useremailSearch' => tt('User e-mail', 'booking'),
			'sum' => tt('Booking price', 'booking'),
			'date_created' => tt('Creation date', 'booking'),
			'dateCreated' => tt('Creation date', 'booking'),
			'apartment_id' => tt('Apartment ID', 'booking'),
			'sum_rur' => tt('Booking price (RUR)', 'booking'),
			'sum_usd' => tt('Booking price (USD)', 'booking'),
			'id' => tt('ID', 'apartments'),
			'phone' => Yii::t('common', 'Your phone number'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->with = array('user');

		if ($this->date_start) {
			$criteria->compare('date_start', $this->getDateForMysql($this->date_start));
		}

		if ($this->date_end) {
			$criteria->compare('date_end', $this->getDateForMysql($this->date_end));
		}

		if ($this->date_created) {
			$criteria->compare('date_created', $this->getDateForMysql($this->date_created), true);
		}

		$criteria->compare('status', $this->status);
		$criteria->compare('t.id', $this->id);
		$criteria->compare('user.email', $this->useremailSearch, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'date_created DESC',
			),
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
		));
	}

	public function getDateForMysql($date) {
		$mysqlDate = CDateTimeParser::parse($date, self::getYiiDateFormat());
		return date('Y-m-d', $mysqlDate);
	}

	protected function beforeSave() {
		if (!$this->user_id) {
			return false;
		}

		if($this->scenario == 'bookingform'){
			$this->date_start = date('Y-m-d', CDateTimeParser::parse($this->date_start, self::getYiiDateFormat()));
			$this->date_end = date('Y-m-d', CDateTimeParser::parse($this->date_end, self::getYiiDateFormat()));
		}

		return parent::beforeSave();
	}

	public function paymentSuccess() {
		$this->status = Booking::STATUS_PAYMENTCOMPLETE;
		$this->update(array('status'));

		$notifier = new Notifier;
		$notifier->raiseEvent('onPaymentSuccess', $this, $this->user_id);
	}

	public function getSumLine() {
		if ($this->sum_rur && $this->sum_usd) {
			return $this->sum_rur . ' (' . $this->sum_usd . '$)';
		}
		else if ($this->sum_rur) {
			return $this->sum_rur;
		}
		else if ($this->sum_usd) {
			return $this->sum_usd . ' $';
		}
		return '';
	}

	public static function getDate($mysqlDate, $full = 0) {
		if (!$full) {
			$date = CDateTimeParser::parse($mysqlDate, 'yyyy-MM-dd');
		}
		else {
			$date = CDateTimeParser::parse($mysqlDate, 'yyyy-MM-dd hh:mm:ss');
		}
		return Yii::app()->dateFormatter->format(self::getYiiDateFormat(), $date);
	}

	public static function getJsDateFormat() {
		$dateFormat = 'dd.mm.yy';
		if (Yii::app()->language == 'en') {
			$dateFormat = 'mm/dd/yy';
		}
		return $dateFormat;
	}

	public function getStatuses() {
		return array(
			'' => '',
			Booking::STATUS_NEW => tt('Need admin approve', 'booking'),
			Booking::STATUS_WAITPAYMENT => tt('Wait for payment', 'booking'),
			Booking::STATUS_PAYMENTCOMPLETE => tt('Payment complete', 'booking'),
			Booking::STATUS_DECLINED => tt('Booking declined', 'booking'),
		);
	}

	public function returnStatusHtml() {
		$return = '';
		switch ($this->status) {
			case Booking::STATUS_NEW:
				$return = tt('Need admin approve', 'booking');
				break;
			case Booking::STATUS_WAITPAYMENT:
				$return = tt('Wait for payment', 'booking');
				break;
			case Booking::STATUS_PAYMENTCOMPLETE:
				$return = tt('Payment complete', 'booking');
				break;
			case Booking::STATUS_DECLINED:
				$return = tt('Booking declined', 'booking');
				break;
		}
		return $return;
	}
	
	protected function afterFind() {
		$dateFormat = param('bookingModule_dateFormat', 0) ? param('bookingModule_dateFormat') : param('dateFormat', 'd.m.Y H:i:s');
		$this->dateCreated = date($dateFormat, strtotime($this->date_created));

		return parent::afterFind();
	}

}