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

function param($name, $default = null) {
	if (isset(Yii::app()->params[$name]))
		return Yii::app()->params[$name];
	else
		return $default;
}

function tt($string, $module = null) {
	if ($module === null) {
		if (Yii::app()->controller->module) {
			return Yii::app()->controller->module->t($string);
		}
		return Yii::t('common', $string);
	}
	return Yii::t('module_'.$module, $string);
	//return Yii::app()->getModule($module)->t($string);
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		if($objects){
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir . "/" . $object) == "dir")
						rrmdir($dir . "/" . $object);
					else
						unlink($dir . "/" . $object);
				}
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function issetModule($module) {
    if (is_array($module)) {
        foreach ($module as $module_name) {
            if (!isset(Yii::app()->modules[$module_name])) {
                return false;
            }
        }
        return true;
    }
    return isset(Yii::app()->modules[$module]);
}

function deb($mVal, $sName = '') {
	$aCol = array('#FFF082','#BAFF81','#BAFFD7','#F0D9D7');
	$color = $aCol[RAND(0,3)];
	echo "<div style='background-color: $color;'><PRE><strong>$sName = </strong>";
	if (is_array($mVal)) echo '<br>';
	print_r($mVal);
	echo "</PRE></div>";
}

function throw404(){
	throw new CHttpException(404,'The requested page does not exist.');
}

function showMessage($messageTitle, $messageText , $breadcrumb = '', $isEnd = true) {
	 Yii::app()->controller->render('message', array('breadcrumb' => $breadcrumb,
					'messageTitle' => $messageTitle,
					'messageText'  => $messageText));

	 if ($isEnd) {
		Yii::app()->end();
	 }
}
