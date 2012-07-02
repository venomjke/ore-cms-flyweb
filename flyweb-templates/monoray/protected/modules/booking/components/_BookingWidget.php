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

class PaymentWidget extends CWidget
{
	public $orderSum;
	public $orderId;

	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.payment.views');
	}
    
	/*public function run() {
		$connection=Yii::app()->db;

		$sql='SELECT login,password1,mode FROM paysystem WHERE name="robokassa"';
		$command=$connection->createCommand($sql);
		$settings=$command->queryRow();

		$settings['sign']=md5($settings['login'].':'.$this->orderSum.':'.$this->orderId.':'.$settings['password1']);
		
		$this->render('widgetPayment',array(
			'settings'=>$settings,
			'orderId'=>$this->orderId,
			'orderSum'=>$this->orderSum,
		));
    }*/

}
