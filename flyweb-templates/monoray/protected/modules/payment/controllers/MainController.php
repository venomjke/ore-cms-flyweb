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

class MainController extends ModuleUserController{

	/*
	 * Принимаем ответ от платежки, создаем нужную модель и обрабатываем платеж
	 */
	public function actionIncome(){
		$paysystem = $this->_createPaymentSystemModel();
		if($paysystem === null){
			return;
		}

		$result = $paysystem->payModel->processRequest();

		// Обрабатываем успешный платеж
		if($result['result'] == 'success'){
			$payment = Payments::model()->findByPk($result['id']);
			if($payment){
				if($payment->status != Payments::STATUS_PAYMENTCOMPLETE){
					$payment->status = Payments::STATUS_PAYMENTCOMPLETE;
					$payment->update(array('status'));

					// Вызываем обработку в моделе Booking (там выставляется статус брони и вызывается оповещение)
					$payment->order->paymentSuccess();
				}

				$paysystem->payModel->echoSuccess();
				Yii::app()->user->setFlash('success', 'Платеж успешно проведен');
				$this->redirect(array('/usercpanel/main/index'));
			}
		}

		// Обрабатываем неудачный платеж
		if($result['result'] == 'fail'){
			// Если в ответе от платежки есть id платежа - ставим ему статус "Отменен"
			if($result['id']){
				$payment = Payments::model()->findByPk($result['id']);
				if($payment){

					if($payment->status == Payments::STATUS_WAITPAYMENT && $payment->order->status == Booking::STATUS_WAITPAYMENT){
						
						$payment->status = Payments::STATUS_DECLINED;
						$payment->update(array('status'));

						$paysystem->payModel->echoDeclined();
						Yii::app()->user->setFlash('error', 'Платеж отменен');
					}
					
					$this->redirect(array('/usercpanel/main/index'));
				}
			}
			
			$paysystem->payModel->echoError();
			$this->render('message', array(
				'message' => 'При обработке платежа возникла ошибка',
			));
		}
	}

	private function _createPaymentSystemModel($name = null){
		if($name === null){
			$name = $_REQUEST['sys'];
		}
		$paysystem = Paysystem::model()->findByAttributes(
			array('name' => $name)
		);

		if($paysystem === null){
			return null;
		}

		$paysystem->createPayModel();

		if($paysystem->payModel === null){
			return null;
		}
		return $paysystem;
	}

	/*
	 * Показываем форму с информацией и выбором способа оплаты
	 */
	public function actionPaymentform($id){
		$booking = Booking::model()->findByPk($id);
		if($booking === null || $booking->user_id != Yii::app()->user->getId()){
			throw404();
		}

		if($booking->status != Booking::STATUS_WAITPAYMENT){
			Yii::app()->user->setFlash('error', 'Бронь имеет недопустимый статус! Оплата невозможна.');
			$this->redirect(array('/booking/main/view', 'id' => $booking->id));
		}

		// Список доступных платежек
		$paySystems = Paysystem::getPaysystems();

		$this->render('paymentform', array(
			'paySystems' => $paySystems,
			'booking' => $booking,
		));
	}

	/*
	 * Создаем запись в таблице платежей и обрабатываем запрос через выбранную платежную сетему
	 */
	public function actionProcessform(){
		$id = $_REQUEST['id'];
		$booking = $_REQUEST['booking'];

		$paysystem = Paysystem::model()->findByPk($id);
		$booking = Booking::model()->findByPk($booking);
		if(!$paysystem || !$booking){
			throw404();
		}
		
		if($booking->status != Booking::STATUS_WAITPAYMENT){
			Yii::app()->user->setFlash('error', 'Бронь имеет недопустимый статус! Оплата невозможна.');
			$this->redirect(array('/booking/main/view', 'id' => $booking->id));
		}

		$paysystem->createPayModel();
		if($paysystem->payModel === null){
			throw404();
		}

		// Создаем платеж и ставим ему статус "Ожидает оплаты"
		$payment = new Payments;
		$payment->order_id = $booking->id;
		$payment->sum = $booking->sum_rur;
		$payment->status = Payments::STATUS_WAITPAYMENT;
		$payment->paysystem_id = $paysystem->id;

		$payment->save();

		// Передаем платеж на обработку в модель платежки.
		// Приложение либо звершается (происходит редирект по нужному адресу),
		// либо выдает сообщение, которое будет отображено пользователю
		$message = $paysystem->payModel->processPayment($payment);

		$this->render('result', array(
			'payment' => $payment,
			'paysystem' => $paysystem,
			'message' => $message,
		));
	}
}
