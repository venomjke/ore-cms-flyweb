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
class SimpleformModel extends CFormModel {
	public $username;
	public $comment;
	public $useremail;
	public $phone;
	public $date_start;
	public $date_end;
	public $time_in;
	public $time_out;
	public $rooms;
	public $user_id;
	public $password;
	public $email;
	public $type;

	public $time_inVal;
	public $time_outVal;

	public function rules() {
		return array(
			array('rooms, type, ' . (Yii::app()->user->isGuest ? 'useremail, username, phone, rooms' : ''), 'required', 'on' => 'forrent'),
			array('rooms, type, ' . (Yii::app()->user->isGuest ? 'useremail, username, phone, rooms' : ''), 'required', 'on' => 'forbuy'),			
			array('useremail', 'email'),
			array('rooms', 'numerical', 'min' => 1),
			array('useremail, username', 'length', 'max' => 128),
			array('phone', 'required', 'on' => 'bookingform'),
			array('comment, rooms, type', 'safe'),
		);
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
		$dateStart = CDateTimeParser::parse($this->date_start, Booking::getYiiDateFormat()); // format to unix timestamp
		$dateEnd = CDateTimeParser::parse($this->date_end, Booking::getYiiDateFormat()); // format to unix timestamp

		if ($param == 'date_start' && $dateStart < CDateTimeParser::parse(date('Y-m-d'), 'yyyy-MM-dd')) {
			$this->addError('date_start', tt('Wrong check-in date', 'booking'));
		}
		if ($param == 'date_end' && $dateEnd <= $dateStart) {
			$this->addError('date_end', tt('Wrong check-out date', 'booking'));
		}
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
			'useremail' => tt('Email', 'booking'),
			'phone' => Yii::t('common', 'Your phone number'),
			'rooms' => Yii::t('common', 'Number of rooms'),
			'type' => Yii::t('common', 'I want'),
		);
	}}