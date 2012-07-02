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

class Offline extends CFormModel implements PaymentSystem {
	public $text;

	public function rules(){
		return array(
			array('text', 'required'),
		);
	}

	public function attributeLabels(){
		return array(
			'text' => 'Реквизиты для оплаты и описание процесса',
		);
	}

	public function processRequest(){}

	public function echoSuccess(){}

	public function echoDeclined(){}

	public function echoError(){}

	public function processPayment($payment){
		$payment->status = Payments::STATUS_WAITOFFLINE;
		$payment->update(array('status'));

		$notifier = new Notifier;
		$notifier->raiseEvent('onOfflinePayment', $payment);

		return 'Спасибо! Уведомление о Вашем платеже отправлено администратору. Бронь будет подтверждена после получения оплаты.';
	}

	public function printInfo(){}

	public function getDescription(){
		return $this->text;
	}
	
}