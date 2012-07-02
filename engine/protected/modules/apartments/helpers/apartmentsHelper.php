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
class apartmentsHelper {
	public static function getApartments($limit = 10, $usePagination = 1, $all = 1, $criteria = null){
		$pages = array();
		$apartments = array();

		Yii::app()->getModule('apartments');

		if($criteria === null){
			$criteria = new CDbCriteria;
			if(!$all){
				$criteria->condition = 'active=1';
			}
		}

		$priceCurrency = (Yii::app()->language == 'ru' ? 'rur' : 'usd');

		$sort = new CSort('Apartment');
		$sort->attributes = array(
			'price' => 'price_from_'.$priceCurrency,
			'rating' => 'rating',
		);
		if(!$criteria->order){
			$sort->defaultOrder = 't.sorter DESC';
		}
		$sort->applyOrder($criteria);

		$sorterLinks = self::getSorterLinks($sort);
		$criteria->addCondition('t.owner_id = 1 OR t.owner_active = 1');

		if($usePagination){
			$pages = new CPagination(Apartment::model()->count($criteria));
			$pages->pageSize = $limit;
			$pages->applyLimit($criteria);
		}
		else{
			$criteria->limit = $limit;
		}

		$apartments = Apartment::model()
			->cache(param('cachingTime', 1209600), Apartment::getImagesDependency())
			->with(array('images'))
			->findAll($criteria);
		return array(
			'pages' => $pages,
			'apartments' => $apartments,
			'sorterLinks' => $sorterLinks,
		);
	}

	public static function getSorterLinks($sort){
        $HtmlOption = array('onClick'=>'reloadApartmentList(this.href); return false;');
		$return = array(
			$sort->link('price', tt('Sorting by price', 'quicksearch'), $HtmlOption),
			$sort->link('rating', tt('Sorting by rating', 'quicksearch'), $HtmlOption),
		);
		return $return;
	}
}
