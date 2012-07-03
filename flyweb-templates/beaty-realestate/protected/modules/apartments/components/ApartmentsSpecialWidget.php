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

class ApartmentsSpecialWidget extends CWidget {
	public $count = null;

	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.apartments.views');
	}

	public function run() {
		Yii::import('application.modules.apartments.helpers.apartmentsHelper');
		$criteria = new CDbCriteria();
		$criteria->condition = "active=1 and is_special_offer=1";
		$result = apartmentsHelper::getApartments(param('module_apartments_widgetApartmentsItemsPerPage', 5), 1, 0, $criteria);
		
		if($this->count){
			$result['count'] = $this->count;
		}

		$this->render('widgetApartmentsSpecial_list', $result);
	}
}