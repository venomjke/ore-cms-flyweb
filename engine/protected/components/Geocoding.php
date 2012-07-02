<?php

class Geocoding {
	static function getGeocodingInfo($apiURL){
		$rawData = '';
		if( function_exists('curl_version')  ){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $apiURL);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$rawData = curl_exec($ch);
			curl_close($ch);
		}
		else // no CUrl, try differently
			$rawData = file_get_contents($apiURL);
		return $rawData;
	}

	static function getGeocodingInfoJsonGoogle($city, $address, $centerX = '', $centerY = '', $spanX = '', $spanY = ''){
		$address_string = ($city ? $city.', ' : '').$address;
		$apiURL = 'http://maps.google.com/maps/geo?q='.urlencode($address_string).'&output=json&sensor=false'.
				(($centerX && $centerY && $spanX && $spanY) ? '&ll='.$centerY.','.$centerX.'&spn='.$spanY.','.$spanX : '');
		return json_decode(self::getGeocodingInfo($apiURL));
	}

	static function getGeocodingInfoJsonYandex($city, $address, $centerX = '', $centerY = '', $spanX = '', $spanY = ''){
		$key = param('module_apartments_ymapsKey');

		$address_string = ($city ? $city.', ' : '').$address;
		$apiURL = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($address_string).'&format=json'.
				(($centerX && $centerY && $spanX && $spanY) ? '&ll='.$centerY.','.$centerX.'&spn='.$spanY.','.$spanX : '').
				'&key='.$key;
		return json_decode(self::getGeocodingInfo($apiURL));
	}

	static function getCoordsByAddress($address, $city = null){
		$return = array();
		if (param('useGoogleMap', 1)) {
			if($city){
				$result = self::getGeocodingInfoJsonGoogle($city, $address);
			} else {
				$result = self::getGeocodingInfoJsonGoogle(param('defaultCity', 'Москва'), $address,
					param('module_apartments_mapsCenterX', 37.620717508911184), param('module_apartments_mapsCenterY', 55.75411314653655),
					param('module_apartments_mapsSpanX', 0.552069), param('module_apartments_mapsSpanY', 0.400552));
			}
			if(isset($result->results[0])){
				if(isset($result->results[0]->geometry->location)){
					$return['lat'] = $result->results[0]->geometry->location->lat;
					$return['lng'] = $result->results[0]->geometry->location->lng;
				}
			}
		} elseif (param('useYandexMap', 1)) {
			if($city){
				$result = self::getGeocodingInfoJsonYandex($city, $address);
			} else {
				$result = self::getGeocodingInfoJsonYandex(param('defaultCity', 'Москва'), $address,
					param('module_apartments_mapsCenterX', 37.620717508911184), param('module_apartments_mapsCenterY', 55.75411314653655),
					param('module_apartments_mapsSpanX', 0.552069), param('module_apartments_mapsSpanY', 0.400552));
			}

			if(isset($result->response->GeoObjectCollection->featureMember[0])){
				if(isset($result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos)){
					$pos = explode(' ', $result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
					$return['lat'] = $pos[1];
					$return['lng'] = $pos[0];;
				}
			}
		}

		return $return;

	}
}