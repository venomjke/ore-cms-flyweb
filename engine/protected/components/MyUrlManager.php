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
class MyUrlManager extends CUrlManager{

	private $_myRules = array();
	private $_replaceSymb = array(',', '/','!','#','~','@','%','^','&', '?','/','\\','|','-', '+', '.', ' ', '--');

	
	public function init(){
		$this->_myRules = array(
			array(
				'replace' => array(
					'<title:.*?>',
					'<id:\d+>',
					Yii::t('seo', 'news'),
				),
				'route' => 'news/main/view',
				'pattern' => param('module_news_newsSeoPattern', '::text/::title-::id.htm'),
			),
			array(
				'replace' => array(
					'<title:.*?>',
					'<id:\d+>',
					Yii::t('seo', 'faq'),
				),
				'route' => 'articles/main/view',
				'pattern' => param('module_articles_articleSeoPattern', '::text/::title-::id.htm'),
			),
			array(
				'replace' => array(
					'<title:.*?>',
					'<id:\d+>',
					Yii::t('seo', 'apartments'),
				),
				'route' => 'apartments/main/view',
				'pattern' => param('module_apartments_apartmentSeoPattern', '::text/::title-::id.htm'),
			),
			array(
				'replace' => array(
					'<title:.*?>',
					'<id:\d+>',
					Yii::t('seo', 'page'),
				),
				'route' => 'menumanager/main/view',
				'pattern' => param('module_infopages_pageSeoPattern', '::title-::id.htm'),
			),
		);

		if (param('useReferenceLinkInView')) {
			Yii::import('application.modules.referencevalues.models.ReferenceValues');
			// Просмотр объявления - клик по значению из справочника
			$allReferenceValues = ReferenceValues::model()->cache(param('cachingTime', 1209600), ReferenceValues::getDependency())->findAll();

			foreach ($allReferenceValues as $value) {
				$this->addRules(array(
					'service-'.$value->id => array('quicksearch/main/mainsearch',
						'urlSuffix'=>'',
						'caseSensitive'=>false,
						'defaultParams' => array('serviceId' => $value->id),
						'parsingOnly' => true,
					),
				));
			}
		}

		$this->parseMyInitRules();
		parent::init();
	}

	public function parseMyInitRules(){
		if(!$this->_myRules){
			return;
		}
		foreach($this->_myRules as $rule){
			$seoPattern = $this->parseMyLink($rule['replace'], $rule['pattern']);
			if($seoPattern){
				$this->rules[$seoPattern] = $rule['route'];

				$this->addRules(array(
					$seoPattern => $rule['route'],
				));
			}
		}
	}

    public function createUrl($route,$params=array(),$ampersand='&'){
		if($this->_replaceSymb && isset($params['title'])){
			$params['title'] = str_replace($this->_replaceSymb, '-', $params['title']);
			//$params['title'] = mb_strtolower($params['title'], 'UTF-8');
		}

		return parent::createUrl($route, $params, $ampersand);
    }

	private function parseMyLink($replaceTo = array(), $seoPattern = ''){
		if($replaceTo){
			if($seoPattern){
				$seoPattern = str_replace(array(
					'::title',
					'::id',
					'::text',
				), $replaceTo, $seoPattern);
				return $seoPattern;
			}
		}
		return false;
	}
}