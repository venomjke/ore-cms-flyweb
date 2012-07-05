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

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

	public $layout = '//layouts/index';
	public $infoPages = array();
	public $menuTitle;
	public $menu = array();
	public $breadcrumbs = array();
	public $pageKeywords;
	public $pageDescription;
	public $adminTitle = '';
	public $aData;

	function init() {
		if (!file_exists(ALREADY_INSTALL_FILE) && !(Yii::app()->controller->module && Yii::app()->controller->module->id == 'install')) {
			$this->redirect(array('/install'));
		}

		$this->pageTitle = param('siteName_'.Yii::app()->language);
		Yii::app()->name = $this->pageTitle;

		$this->pageKeywords = param('siteKeywords_'.Yii::app()->language);
		$this->pageDescription = param('siteDescription_'.Yii::app()->language);

		if(Yii::app()->getModule('menumanager')){
			if(!(Yii::app()->controller->module && Yii::app()->controller->module->id == 'install')){
				$this->infoPages = Menu::getMenuItems();
			}
		}

		$this->aData['adminMenuItems'] = array(
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_ads.png" />',
				'url' => array('/apartments/backend/main/admin'), 'linkOptions' => array(
					'class' => 'adminMainNavItem',
					'title' => Yii::t('module_apartments', 'Manage apartments')
				),
			),
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_slider.png" />', 
				'url' => array('/slider/backend/main/admin'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => 'Управление слайдером')
			),

			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_comments.png" />',
				'url' => array('/comments/backend/main/admin'),
				'linkOptions' => array(
					'class' => 'adminMainNavItem',
					'title' => Yii::t('module_comments', 'Manage comments')
				)
			),

			/*
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_booking.png" />', 
				'url' => array('/booking/backend/main/admin'), 
				'linkOptions' => array(
				                        'class' => 'adminMainNavItem', 
										'title' => Yii::t('module_booking', 'Manage bookings')
									)
			),
			*/
			/*
			array(
			'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_payment.png" />', 
			'url' => array('/payment/backend/main/admin'), 
			'linkOptions' => array(
			                        'class' => 'adminMainNavItem', 
									'title' => Yii::t('module_payment', 'Manage payments')
									)
			),*/
			/*
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_users.png" />', 
				'url' => array('/users/backend/main/admin'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('common', 'User managment'))
			),
			*/
			array('label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_news.png" />', 
			      'url' => array('/news/backend/main/admin'), 
				  'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_news', 'Manage news'))
			),
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_infopages.png" />',
				'url'=>array('/menumanager/backend/main/admin'),
				'linkOptions'=>array(
					'class'=>'adminMainNavItem',
					'title' => 'Управление верхним меню'
				)
			),
			array(
			   'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_references.png" />', 'url' => array('/site/viewreferences'), 
			   'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('common', 'Manage references'))
			),
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_qa.png" />', 
				'url' => array('/articles/backend/main/admin'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_articles', 'Manage FAQ'))
			),
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_admin_pass.png" />', 'url' => array('/adminpass/backend/main/index'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_adminpass', 'Change admin password'))
			),
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_settings.png" />', 
				'url' => array('/configuration/backend/main/admin'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_configuration', 'Settings'))
			)
			/*,
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/manage_payment_settings.png" />', 
				'url' => array('/payment/backend/paysystem/index'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_payment', 'Payment System Settings'))
			),*/
			/*
			array(
				'label' => '<img src="'.Yii::app()->request->baseUrl.'/images/adminmenu/news_product.png" />', 
				'url' => array('/news/backend/main/product'), 
				'linkOptions' => array('class' => 'adminMainNavItem', 'title' => Yii::t('module_news', 'News product'))
			)*/
		);

		$this->aData['userCpanelItems'] = array(
			array(
				'label' => 'Добавить объявление',
				'url' => array('/userads/main/create'),
				'visible' => param('useUserads', 0) == 1 && Yii::app()->user->getState('isAdmin')
			),
			array(
				'label' => '|',
				'visible' => param('useUserads', 0) == 1 && Yii::app()->user->getState('isAdmin')
			),
			array('label' => Yii::t('common', 'Contact us'), 'url' => array('/contactform/main/index')),
			/*
			array('label' => '|'),
			array(
				'label' => Yii::t('common', 'Reserve apartment'),
				'url' => array('/booking/main/mainform'),
				'visible' => Yii::app()->user->getState('isAdmin') === null,
				'linkOptions' => array('class' => 'fancy'),
			),*/
			/*
			array('label' => '|', 'visible' => Yii::app()->user->getState('isAdmin') === null),
			*/
			/*
			array(
				'label' => Yii::t('common', 'Control panel'),
				'url' => array('/usercpanel/main/index'),
				'visible' => !Yii::app()->user->isGuest
			),
			*/
			array('label' => '|', 'visible' => Yii::app()->user->getState('isAdmin') !== null),
			array('label' => Yii::t('common', 'Logout'), 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
		);
		$this->aData['topMenuItems'] = $this->infoPages;
		parent::init();
	}

	public static function disableProfiler() {
		if (Yii::app()->getComponent('log')) {
			foreach (Yii::app()->getComponent('log')->routes as $route) {
				if (in_array(get_class($route), array('CProfileLogRoute', 'CWebLogRoute', 'YiiDebugToolbarRoute'))) {
					$route->enabled = false;
				}
			}
		}
	}

	public function getMainLangsArr() {
		$langs = param('languages', array('ru', 'en'));
		$return = array();
		if ($langs) {
			foreach ($langs as $lang) {
				$return[$lang]['title'] = $this->getLangTranslate($lang);
				$return[$lang]['code'] = $lang;
				$return[$lang]['link'] = $this->getLangParams($lang);
			}
		}
		return $return;
	}

	public function getLangTranslate($lang) {
		if ($lang == 'ru') {
			return Yii::t('common', 'Russian');
		}
		if ($lang == 'en') {
			return Yii::t('common', 'English');
		}
	}

	public function getLangParams($lang) {
		if ($lang != Yii::app()->language) {
			$params = $this->actionParams;
			$params['lang'] = (Yii::app()->language == 'ru') ? 'en' : 'ru';
			return $this->createUrl('/'.$this->route, $params);
		}
	}

	public function getLangsArr() {
		$langs = param('languages', array('ru', 'en'));
		$return = array();
		if ($langs) {
			foreach ($langs as $lang) {
				$return[$lang] = $this->getLangTranslate($lang);
			}
		}
		return $return;
	}

}