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

class SelectToSlider extends CFormModel {

	public $modulePathBase;
	public $sitePath;
	public $assetsPath;
		
	public function init() {
		$this->preparePaths();
		$this->publishAssets();
	}
		
	public function preparePaths() {
		$this->modulePathBase = dirname(__FILE__) . '/../';
		$this->sitePath = Yii::app()->basePath . '/../';
		$this->assetsPath = $this->modulePathBase . '/assets';
		
	}
	
	public function publishAssets() {
		if (is_dir($this->assetsPath)) {
			$baseUrl = Yii::app()->assetManager->publish($this->assetsPath);

			if (!Yii::app()->clientScript->isCssFileRegistered($baseUrl . '/css/ui/jquery-ui.multiselect.css')) {
				Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/ui/jquery-ui.multiselect.css');
			}
			
			if (!Yii::app()->clientScript->isCssFileRegistered($baseUrl . '/css/redmond/jquery-ui-1.7.1.custom.css')) {
				Yii::app()->clientScript->registerCssFile($baseUrl . '/css/redmond/jquery-ui-1.7.1.custom.css');
			}
			if (!Yii::app()->clientScript->isCssFileRegistered($baseUrl . '/css/ui.slider.extras.css')) {
				Yii::app()->clientScript->registerCssFile($baseUrl . '/css/ui.slider.extras.css');
			}
			if (!Yii::app()->clientScript->isCssFileRegistered($baseUrl . '/css/search-form-select.css')) {
				Yii::app()->clientScript->registerCssFile($baseUrl . '/css/search-form-select.css');
			}
			
			if (!Yii::app()->clientScript->isScriptFileRegistered($baseUrl . '/js/jquery-ui-1.8.2.min.js')) {
				Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/jquery-ui-1.8.2.min.js', CClientScript::POS_END);
			}
			if (!Yii::app()->clientScript->isScriptFileRegistered($baseUrl . '/js/selectToUISlider.jQuery.js')) {
				Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/selectToUISlider.jQuery.js', CClientScript::POS_END);
			}
			if (!Yii::app()->clientScript->isScriptRegistered('fixToolTipColor')) {
				Yii::app()->clientScript->registerScript('fixToolTipColor', '
					function fixToolTipColor(){
						/*grab the bg color from the tooltip content - set top border of pointer to same*/
						$(".ui-tooltip-pointer-down-inner").each(function(){
							var bWidth = $(".ui-tooltip-pointer-down-inner").css("borderTopWidth");
							var bColor = $(this).parents(".ui-slider-tooltip").css("backgroundColor")
							$(this).css("border-top", bWidth+" solid "+bColor);
						});	
					}
				', CClientScript::POS_READY);
			}
		}
	}
}