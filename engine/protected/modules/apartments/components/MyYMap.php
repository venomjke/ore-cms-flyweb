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

class MyYMap {
    
	private static $_instance;
	protected $scripts = array();

	public static function init(){
		if ( ! isset(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className;
		}
		return self::$_instance;
	}

	public function processScripts(){
		Yii::app()->clientScript->registerScript('yMap', implode('', $this->scripts), CClientScript::POS_READY);
	}

    public function createMap(){
            
		$key = param('module_apartments_ymapsKey');
		

		$icon['href'] = Yii::app()->request->baseUrl."/images/house.png";
		$icon['size'] = array('x'=>32, 'y'=>37);
		$icon['offset'] = array('x'=>-16, 'y' =>-16.5);

		Yii::app()->getClientScript()->registerScriptFile(
			'http://api-maps.yandex.ru/1.1/index.xml?key='.$key.'&modules=pmap',
        CClientScript::POS_END); 

		$this->scripts[] =
		'
			var our_style = new YMaps.Style();
			our_style.iconStyle = new YMaps.IconStyle();
			our_style.iconStyle.href = "'.$icon['href'].'";
			our_style.iconStyle.size = new YMaps.Point('.$icon['size']['x'].', '.$icon['size']['y'].');
			our_style.iconStyle.offset = new YMaps.Point('.$icon['offset']['x'].', '.$icon['offset']['y'].');
			var map = new YMaps.Map(document.getElementById("ymap"));
			YMaps.MapType.PMAP.getName = function () { return "'.Yii::t('common', 'People (scheme)').'"; };
			YMaps.MapType.PHYBRID.getName = function () { return "'.Yii::t('common', 'People (hybrid)').'"; };
			map.addControl(new YMaps.TypeControl([YMaps.MapType.MAP, YMaps.MapType.SATELLITE, YMaps.MapType.HYBRID, YMaps.MapType.PMAP, YMaps.MapType.PHYBRID], [0, 1, 2, 3, 4], {"width" : 200})); 
			map.addControl(new YMaps.ToolBar());
			map.addControl(new YMaps.Zoom());
			map.addControl(new YMaps.ScaleLine());
			map.enableScrollZoom();
			var markerCollection = new YMaps.GeoObjectCollection();
		';
//$this->setCenter(55.75411314653655, 37.620717508911184);
		
	
    }

    public function setCenter($lat, $lng) {
		$this->scripts[] = '
			var centerpoint = new YMaps.GeoPoint('.$lng.', '.$lat.');
			map.setCenter(centerpoint);
		';
    }
	public function setBounds($lat_min, $lat_max, $lng_min, $lng_max) {
		$this->scripts[] = '
			var bounds = new YMaps.GeoBounds(new YMaps.GeoPoint('.$lng_min.', '.$lat_min.'), new YMaps.GeoPoint('.$lng_max.', '.$lat_max.'));
			map.setBounds(bounds);
		';
    }
	
	public function setGeoCenter($city) {
		$this->scripts[] = '
			var geocoder = new YMaps.Geocoder("'.$city.'", {kind: "city", results: 1});
			YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
				if (this.length()) {
					map.setCenter(this.get(0).getGeoPoint());
				} else {
					var centerpoint = new YMaps.GeoPoint(37.620717508911184, 55.75411314653655);
					map.setCenter(centerpoint);
				}
			});				
		';
    }
    
    public function setZoom($zoom) {
		$this->scripts[] = '
			map.setZoom('.$zoom.');
		';
    }
	public function changeZoom($zoom) {
		$this->scripts[] = '
			map.zoomBy('.$zoom.');
		';
    }
    
	public function addMarker($lat, $lng, $content, $multyMarker = 0, $model = null) {
		$content = $this->filterContent($content);

		$draggable = ((Yii::app()->user->getState('isAdmin') || param('useUserads', 1) && (!Yii::app()->user->isGuest && Yii::app()->user->id == $model->owner_id) ) && !$multyMarker) ? ", draggable: true" : "";

		$this->scripts[] = '
			var placemark = new YMaps.Placemark(new YMaps.GeoPoint('.$lng.', '.$lat.'), {style: our_style, hideIcon: false'.$draggable.'});
			placemark.setBalloonContent("'.$content.'");
			placemark.setBalloonOptions({maxWidth:300});
			map.addOverlay(placemark);
			'.(($multyMarker) ? '' : 'placemark.openBalloon();');
	}

	public function filterContent($content){
		$content = preg_replace('/\r\n|\n|\r/', "\\n", $content);
		$content = preg_replace('/(["\'])/', '\\\\\1', $content);

		return $content;
	}

	public function actionYmap($id, $model, $inMarker){

		$centerX = param('module_apartments_mapsCenterX', 37.620717508911184);
		$centerY = param('module_apartments_mapsCenterY', 55.75411314653655);
		$defaultCity = param('defaultCity', 'Москва');
		
		if($model->city && $model->city->name){
			$centerX = 0;
			$centerY = 0;
			$defaultCity = $model->city->name;
		}
		
		
		$res = $this->createMap();

		// If we have already created marker - show it
		if ($model->lat && $model->lng) {
			// Preparing InfoWindow with information about our marker.
		    $this->addMarker($model->lat, $model->lng, $inMarker, 0, $model);
		    $this->setCenter($model->lat, $model->lng);
			$this->setZoom(param('module_apartments_mapsZoomApartment', 15));
			
		    if(Yii::app()->user->getState('isAdmin') || param('useUserads', 1) && !Yii::app()->user->isGuest && Yii::app()->user->id == $model->owner_id){
				$this->scripts[] = '
					YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {
						$.ajax({
							type:"POST",
							url:"'.Yii::app()->controller->createUrl('savecoords', array('id' => $model->id) ).'",
							data:({lat: obj.getGeoPoint().getLat(), lng: obj.getGeoPoint().getLng()}),
							cache:false
						})
					});';
		    }
		} else {
			if(Yii::app()->user->getState('isAdmin') || param('useUserads', 1) && !Yii::app()->user->isGuest && Yii::app()->user->id == $model->owner_id){
				
				if ($centerX && $centerY) {
					$this->setCenter($centerY, $centerX);
				} else {
					$this->setGeoCenter($defaultCity);
				}
				$this->setZoom(param('module_apartments_mapsZoomCity', 11));
				
				$inMarker = $this->filterContent($inMarker);
				$this->scripts[] = '
					var clickEventListener = YMaps.Events.observe(map, map.Events.Click, function (map, mEvent) {
					var placemark = new YMaps.Placemark(mEvent.getGeoPoint(),  {style: our_style, hideIcon: false, draggable: true});
						placemark.setBalloonContent("'.$inMarker.'");
						placemark.setBalloonOptions({maxWidth:300});
						map.addOverlay(placemark);
						$.ajax({
							type:"POST",
							url:"'.Yii::app()->controller->createUrl('savecoords', array('id' => $model->id) ).'",
							data:({lat: mEvent.getGeoPoint().getLat(), lng: mEvent.getGeoPoint().getLng()}),
							cache:false
						});
						placemark.openBalloon();
						clickEventListener.cleanup();
						YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {
							$.ajax({
								type:"POST",
								url:"'.Yii::app()->controller->createUrl('savecoords', array('id' => $model->id) ).'",
								data:({lat: obj.getGeoPoint().getLat(), lng: obj.getGeoPoint().getLng()}),
								cache:false
							})
						});
					}, this);';
			}
		}
		$this->processScripts();
		return true;
	}
}