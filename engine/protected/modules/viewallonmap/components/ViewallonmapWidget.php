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

class ViewallonmapWidget extends CWidget {
	public $usePagination = 1;
	public $criteria = null;
	public $count = null;

	public function run() {
		Yii::app()->getModule('apartments');
		if(param('useYandexMap', 1)) {
		    echo $this->render('application.modules.apartments.views.backend._ymap', '', true);
		    MyYMap::init()->createMap();
		} else
		    $result = MyGMap::creatMap();

		$model = new Apartment;

		$criteria = new CDbCriteria;
		$lang = Yii::app()->language;
		$criteria->select = 'lat, lng, id, address_'.$lang.', title_'.$lang.', address_'.$lang;
		$criteria->condition = 'lat <> "" AND lat<>"0" AND active=1';


		$cachingTime = param('shortCachingTime', 3600*4);
		$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment}}');

		$apartments = Apartment::model()->cache($cachingTime, $dependency)->with('images')->findAll($criteria);
		if(param('useYandexMap', 1)) {
			$lats = array();
			$lngs = array();
			foreach($apartments as $apartment){
				$lats[]	=	$apartment->lat;
				$lngs[]	=	$apartment->lng;
				$result = MyYMap::init()->addMarker($apartment->lat, $apartment->lng,
					$this->render('application.modules.apartments.views.backend._marker', array('model' => $apartment), true),1
				);
		    }
			
			if($lats && $lngs){
				MyYMap::init()->setBounds(min($lats),max($lats),min($lngs),max($lngs));
			}
			else {
				$minLat = param('module_apartments_ymapsCenterX') - param('module_apartments_ymapsSpanX')/2;
				$maxLat = param('module_apartments_ymapsCenterX') + param('module_apartments_ymapsSpanX')/2;
				
				$minLng = param('module_apartments_ymapsCenterY') - param('module_apartments_ymapsSpanY')/2;
                $maxLng = param('module_apartments_ymapsCenterY') + param('module_apartments_ymapsSpanY')/2;
				
				MyYMap::init()->setBounds($minLng,$maxLng,$minLat,$maxLat);
			}
			MyYMap::init()->changeZoom(-1);
			MyYMap::init()->processScripts();
		} elseif (param('useGoogleMap', 1)) {
		    foreach($apartments as $apartment){
				$result = MyGMap::addMarker($result, $apartment,
					$this->render('application.modules.apartments.views.backend._marker', array('model' => $apartment), true)
				);
		    }
			//MyGMap::centerMarkers($result);
		    $out['script'] = '';
		    $out['gMap'] = $result['gMap'];

		    echo $this->render('application.modules.apartments.views.backend._gmap', $out, true);
			
		}
	}
}