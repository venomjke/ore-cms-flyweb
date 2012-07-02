<?php

/* * ********************************************************************************************
 *                            CMS Open Real Estate
 *                              -----------------
 * 	version				:	1.2.0
 * 	copyright			:	(c) 2012 Monoray
 * 	website				:	http://www.monoray.ru/
 * 	contact us			:	http://www.monoray.ru/contact
 *
 * This file is part of CMS Open Real Estate
 *
 * Open Real Estate is free software. This work is licensed under a GNU GPL.
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * ********************************************************************************************* */

class Notifier {

	private $_rules;
	private $_userRules;
	private $init = 0;
	private $_oldLang;

	public function init() {
		$this->init = 1;

		$oldLang = Yii::app()->language;
		Yii::app()->setLanguage(param('adminDefaultLang', 'ru'));

		$this->_rules = array(
			'onNewContactform' => array(
				'fields' => array('name', 'email', 'phone', 'body'),
				'subject' => Yii::t('module_notifier', 'New message (contact form)'),
				'body' => Yii::t('module_notifier', 'New message from ::name (::email ::phone). Message text: ::body')."\n",
				'active' => param('module_notifier_adminNewContactform', 1),
			),
			'onNewSimpleBookingForRent' => array(
				'fields' => array('time_inVal', 'time_outVal', 'username', 'comment', 'useremail', 'phone', 'rooms', 'type', 'date_start', 'date_end'),
				'subject' => Yii::t('module_notifier', 'New booking (simple order).'),
				'body' => Yii::t('module_notifier', 'New booking was created (looking for ::rooms room(s) apartment with type "::type"). From ::username (::useremail, phone: ::phone), date start: ::date_start, times in: ::time_inVal, date end: ::date_end, time out: ::time_outVal. Comment: ::comment')."\n",
				'active' => param('module_notifier_adminNewSimpleBooking', 1),
			),
			'onNewSimpleBookingForBuy' => array(
				'fields' => array('username', 'comment', 'useremail', 'phone', 'rooms', 'type'),
				'subject' => Yii::t('module_notifier', 'New booking (simple order).'),
				'body' => Yii::t('module_notifier', 'New booking was created (looking for ::rooms room(s) apartment with type "::type"). From ::username (::useremail, phone: ::phone). Comment: ::comment')."\n",
				'active' => param('module_notifier_adminNewSimpleBooking', 1),
			),
			'onNewComment' => array(
				'fields' => array('rating', 'email', 'body', 'name'),
				'subject' => Yii::t('module_notifier', 'New comment added.'),
				'body' => Yii::t('module_notifier', 'New comment was added. From ::name (::email), rating: ::rating. Message: ::body')."\n".
				Yii::t('module_notifier', 'You can view it at').' ::host'.Yii::app()->controller->createUrl('/comments/backend/main/index'),
				'active' => param('module_notifier_adminNewComment', 1),
			),
			'onNewBooking' => array(
				'subject' => Yii::t('module_notifier', 'New booking.'),
				'body' => Yii::t('module_notifier', 'New booking was added.')."\n".Yii::t('module_notifier', 'You can view it at').' ::host::url',
				'url' => array(
					'/booking/backend/main/view',
					array(
						'id'
					),
				),
				'active' => param('module_notifier_adminNewBooking', 1),
			),
			'onPaymentSuccess' => array(
				'fields' => array('id'),
				'subject' => Yii::t('module_notifier', 'Payment successfully completed.'),
				'body' => Yii::t('module_notifier', 'Payment successfully completed. View booking at:').' ::host::url',
				'url' => array(
					'/booking/backend/main/view',
					array(
						'id',
					),
				),
				'active' => param('module_notifier_adminPaymentSuccess', 1),
			),
			'onNewUser' => array(
				'fields' => array('email', 'username'),
				'subject' => Yii::t('module_notifier', 'User registration'),
				'body' => Yii::t('module_notifier', 'New user ::username ::email registered.')."\n".
				Yii::t('module_notifier', 'You can view and manage users via:').' ::host::url',
				'url' => array(
					'/users/backend/main/admin',
				),
				'active' => param('module_notifier_adminNewUser', 1),
			),
			'onOfflinePayment' => array(
				'fields' => array('sum', 'date_created'),
				'subject' => Yii::t('module_notifier', 'New offline payment'),
				'body' => Yii::t('module_notifier', 'New offline payment ::date_created - ::sum')."\n".
				Yii::t('module_notifier', 'You can view peyments via').' ::host::url',
				'url' => array(
					'/payment/backend/main/admin',
				),
				'active' => param('module_notifier_adminNewOffPay', 1),
			),
			'onRegistrationUser' => array(
				'fields' => array('email', 'username'),
				'subject' => Yii::t('module_notifier', 'User registration'),
				'body' => Yii::t('module_notifier', 'New user ::username ::email registered.')."\n".
				Yii::t('module_notifier', 'You can view and manage users via:').' ::host::url',
				'url' => array(
					'/users/backend/main/admin',
				),
				'active' => param('module_notifier_adminNewUser', 1),
			),
		);
		Yii::app()->setLanguage($oldLang);

		$this->setLang();

		$this->_userRules = array(
			'onNewSimpleBookingForRent' => array(
				'fields' => array(),
				'subject' => Yii::t('module_notifier', 'New booking.'),
				'body' => Yii::t('module_notifier', 'Your order will be reviewed by administrator.')."\n",
				'active' => param('module_notifier_userNewSimpleBooking', 1),
			),
			'onNewSimpleBookingForBuy' => array(
				'fields' => array(),
				'subject' => Yii::t('module_notifier', 'New booking.'),
				'body' => Yii::t('module_notifier', 'Your order will be reviewed by administrator.')."\n",
				'active' => param('module_notifier_userNewSimpleBooking', 1),
			),
			'onPaymentSuccess' => array(
				'fields' => array('id'),
				'subject' => Yii::t('module_notifier', 'Payment successfully completed.'),
				'body' => Yii::t('module_notifier', 'Payment successfully completed. You can view booking status at your control panel:').' ::host::url',
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => param('module_notifier_userPaymentSuccess', 1),
			),
			'onBookingStatusChanged' => array(
				'fields' => array('id', 'tostatus'),
				'subject' => Yii::t('module_notifier', 'Booking status changed.'),
				'body' => Yii::t('module_notifier', 'Booking status changed to "::tostatus". You can view booking at your control panel:').' ::host::url',
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => param('module_notifier_userBookingStatusChanged', 1),
			),
			'onNewBooking' => array(
				'fields' => array(),
				'subject' => Yii::t('module_notifier', 'New booking.'),
				'body' => Yii::t('module_notifier', 'Please wait while administrator approve your booking. You can view additional information and current booking status at your control panel:').' ::host::url',
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => param('module_notifier_userNewBooking', 1),
			),
			'onNewUser' => array(
				'fields' => array('email', 'password'),
				'subject' => Yii::t('module_notifier', 'User registration'),
				'body' => Yii::t('module_notifier', 'Welcome to ::host !')."\n".Yii::t('module_notifier', 'Your login is: ::email')."\n"
				.Yii::t('module_notifier', 'Your password is: ::password')."\n"
				.Yii::t('module_notifier', 'You can login to your control panel via:').' ::host::url'."\n",
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => param('module_notifier_userNewUser', 1),
			),
			'onRecoveryPassword' => array(
				'fields' => array('email', 'password'),
				'subject' => Yii::t('module_notifier', 'Recover password'),
				'body' => Yii::t('module_notifier', 'Your login is: ::email')."\n"
				.Yii::t('module_notifier', 'Your password is: ::password')."\n"
				.Yii::t('module_notifier', 'You can login to your control panel via:').' ::host::url'."\n",
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => 1,
			),
			'onRegistrationUser' => array(
				'fields' => array('email', 'password', 'activateLink'),
				'subject' => Yii::t('module_notifier', 'User registration'),
				'body' => Yii::t('module_notifier', 'Welcome to ::fullhost !')."\n".Yii::t('module_notifier', 'Your login is: ::email')."\n"
					.Yii::t('module_notifier', 'Your password is: ::password')."\n"
					.Yii::t('module_notifier', 'Before use a site')."\n"
					.Yii::t('module_notifier', 'You should activate the account')."\n"
					.Yii::t('module_notifier', 'Link to activate account: ::activateLink')."\n"
					.Yii::t('module_notifier', 'You can login to your control panel via:').' ::host::url'."\n",
				'url' => array(
					'/usercpanel/main/index',
				),
				'active' => param('module_notifier_userNewUser', 1),
			),
		);
		$this->restoreLang();
	}

	public function setLang() {
		if (Yii::app()->user->getState('isAdmin')) {
			$this->_oldLang = Yii::app()->language;
			Yii::app()->setLanguage(param('defaultLang', 'ru'));
		}
	}

	public function restoreLang() {
		if (Yii::app()->user->getState('isAdmin') && $this->_oldLang) {
			Yii::app()->setLanguage($this->_oldLang);
		}
	}

	public function raiseEvent($eventName, $model = null, $userId = 0) {
		if ($this->init == 0) {
			$this->init();
		}
		if (isset($this->_rules[$eventName])) {
			$active = isset($this->_rules[$eventName]['active']) ? $this->_rules[$eventName]['active'] : 0;
			if ($active) {
				$this->_processEvent($this->_rules[$eventName], $model, null, true);
			}
		}

		if ($userId) {
			$user = User::model()->findByPk($userId);
		} else {
			$user = Yii::app()->user;
		}
		if (isset($this->_userRules[$eventName]) && $user) {
			$active = isset($this->_userRules[$eventName]['active']) ? $this->_userRules[$eventName]['active'] : 0;
			if ($active) {
				$this->_processEvent($this->_userRules[$eventName], $model, $user);
			}
		}
	}

	private function _processEvent($rule, $model, $user = null, $toAdmin = false) {
		$body = '';
		if (isset($rule['body'])) {
			$body = $rule['body'];
			$body = str_replace('::host', Yii::app()->request->hostInfo, $body);
			$body = str_replace('::fullhost', Yii::app()->controller->createAbsoluteUrl('/site/index'), $body);

			if($user && !isset($model->username)){
				$body = str_replace('::username', $user->username, $body);
			}
			if (isset($rule['url']) && $model) {
				$params = array();
				if (isset($rule['url'][1])) {
					foreach ($rule['url'][1] as $param) {
						$params[$param] = $model->$param;
					}
					$params['lang'] = param('adminDefaultLang', 'ru');
				}
				$url = Yii::app()->controller->createUrl($rule['url'][0], $params);
				$body = str_replace('::url', $url, $body);
			}
		}

		if (isset($rule['fields']) && isset($rule['body']) && $model) {
			foreach ($rule['fields'] as $field) {
				$body = str_replace('::'.$field, CHtml::encode($model->$field), $body);
			}
		}
		$body = str_replace("\n.", "\n..", $body);

		$subj = '';
		if (isset($rule['subject'])) {
			$subj = '=?UTF-8?B?'.base64_encode($rule['subject']).'?=';
		}

		if ($toAdmin) {
			$to = param('adminEmail');
		} else {
			if (isset($model->useremail) && $model->useremail) {
				$to = $model->useremail;
			} elseif ($user) {
				$to = $user->email;
			} else {
				$to = param('adminEmail');
			}
		}
		$headers = "From: ".param('adminEmail')."\r\nContent-type: text/plain; charset=UTF-8";

		if ($body) {
			@mail($to, $subj, $body, $headers);
		}
	}

}