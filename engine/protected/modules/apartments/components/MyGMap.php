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

class MyGMap {
	public static function creatMap(){
		Yii::import('application.modules.gmaps.*');
		$gMap = new EGMap();
		//$gMap->setWidth(615);
		$gMap->setWidth('100%');
		$gMap->setHeight(550);
		$gMap->zoom = param('module_apartments_mapsZoomCity', 11);
		$mapTypeControlOptions = array(
			'position' => EGMapControlPosition::RIGHT_TOP,
			'style' => EGMap::MAPTYPECONTROL_STYLE_HORIZONTAL_BAR
		);

		$gMap->mapTypeId = EGMap::TYPE_ROADMAP;
		$gMap->mapTypeControlOptions = $mapTypeControlOptions;
				
		// Setting up an icon for marker.
		$icon =	new EGMapMarkerImage(Yii::app()->request->baseUrl."/images/house.png");

		$icon->setSize(32, 37);
		$icon->setAnchor(16, 16.5);
		$icon->setOrigin(0, 0);
		
		$centerX    =	param('module_apartments_mapsCenterX', 37.620717508911184);
		$centerY    =	param('module_apartments_mapsCenterY', 55.75411314653655);
		if ($centerX && $centerY){
			$gMap->setCenter($centerY, $centerX);
		} else {
			$center = $gMap->geocode(param('defaultCity', 'Москва'));
			$gMap->setCenter($center->lat, $center->lng);
		}
		//$gMap->zoom = param('module_apartments_mapsZoomApartment', 16);

		$gMap->enableMarkerClusterer(new EGMapMarkerClusterer());
		
		return array(
			'gMap' => $gMap,
			'icon' => $icon,
		);
	}
	
	public static function centerMarkers($in) {
		echo $in['gMap']->getMarkersFittingZoom();
	}
	
	public static function addMarker($in, $model, $inMarker){
		if($model){
			if($model->lat && $model->lng) {
				$infoWindow = new EGMapInfoWindow($inMarker);
				$marker = new EGMapMarker($model->lat, $model->lng, array('title' => $model->getStrByLang('title'),
						'icon' => $in['icon'], 'draggable'=>false), 'marker',
						array()
					);
				$marker->addHtmlInfoWindow($infoWindow);
				$in['gMap']->addMarker($marker);
			}
		}
		return $in;
	}

	public static function actionGmap($id, $model, $inMarker){

		$res = self::creatMap();
		$gMap = $res['gMap'];
		$icon = $res['icon'];

		// Saving coordinates after user dragged our marker.
		$dragevent = new EGMapEvent('dragend', "function (event) { $.ajax({
										'type':'POST',
										'url':'".Yii::app()->controller->createUrl('savecoords', array('id' => $model->id) )."',
										'data':({'lat': event.latLng.lat(), 'lng': event.latLng.lng()}),
										'cache':false,
									});}", false, EGMapEvent::TYPE_EVENT_DEFAULT);

		$script = array();
		// If we have already created marker - show it	
		if ($model->lat && $model->lng) {
			// Preparing InfoWindow with information about our marker.
			$infoWindow = new EGMapInfoWindow($inMarker/*MainController::getHtmlForMarker($model)*/);


			$event = array();
			$draggable = false;
			if(Yii::app()->user->getState('isAdmin') || param('useUserads', 1) && (!Yii::app()->user->isGuest && Yii::app()->user->id == $model->owner_id) ){
				$event = array('dragevent'=>$dragevent);
				$draggable = true;
			}

			$marker = new EGMapMarker($model->lat, $model->lng, array('title' => $model->getStrByLang('title'),
					'icon' => $icon, 'draggable'=>$draggable), 'marker',
					$event
				);

			$marker->addHtmlInfoWindow($infoWindow);

			$gMap->addMarker($marker);
			$gMap->setCenter($model->lat, $model->lng);
			$gMap->zoom = param('module_apartments_mapsZoomApartment', 16);

			$script = array($infoWindow->getJsName().'.open('.$gMap->getJsName().', '.$marker->getJsName().')');


		// If we don't have marker in database - make sure user can create one
		} else {
			if(!Yii::app()->user->getState('isAdmin') &&
				!(param('useUserads', 1) && !Yii::app()->user->isGuest && Yii::app()->user->id == $model->owner_id)
			){
				return '';
			}
			
			if($model->city && $model->city->name){
				$result = Geocoding::getGeocodingInfoJsonGoogle($model->city->name, '');
				if ($result && isset($result->Status) && $result->Status->code == 200) {
					$coordinates = isset($result->Placemark[0]) ? $result->Placemark[0]->Point->coordinates : '';
					if ($coordinates) {
						$coords['lat'] = $coordinates[1];
						$coords['lng'] = $coordinates[0];

						$gMap->setCenter($coords['lat'], $coords['lng']);
					}
				}
			}
						
			//$gMap->setCenter(param('module_apartments_gmapsCenterX', 55.75411314653655), param('module_apartments_gmapsCenterY', 37.620717508911184));
			// center for Moscow

			// Setting up new event for user click on map, so marker will be created on place and respectful event added.
			$gMap->addEvent(new EGMapEvent('click',
					'function (event) {var marker = new google.maps.Marker({position: event.latLng, map: '.$gMap->getJsName().
					', draggable: true, icon: '.$icon->toJs().'}); '.$gMap->getJsName().
					'.setCenter(event.latLng); var dragevent = '.$dragevent->toJs('marker').
					'; $.ajax({'.
					  '"type":"POST",'.
					  '"url":"'.Yii::app()->controller->createUrl('savecoords', array('id' => $model->id) ).'",'.
					  '"data":({"lat": event.latLng.lat(), "lng": event.latLng.lng()}),'.
					  '"cache":false,'.
					'}); }', false, EGMapEvent::TYPE_EVENT_DEFAULT_ONCE));
		}

		return array(
			'gMap' => $gMap,
			'script' => $script,
		);

		/*return $this->renderPartial('_gmap', array(
			'gMap' => $gMap,
			'script' => $script,
		), true);*/

		/*$this->render('_gmap', array(
			'gMap' => $gMap,
		));*/
	}
}