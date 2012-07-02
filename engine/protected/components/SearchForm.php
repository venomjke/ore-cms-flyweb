<?php

class SearchForm {

	static function stationsInit(){
		$metroStations = array();
		if (isset(Yii::app()->modules['metrostations']) && file_exists(ALREADY_INSTALL_FILE)) {
			$metroStations = MetroStation::getActiveStations();
			if($metroStations === null){
				$metroStations = array();
			}
		}
		return $metroStations;
	}

    static function cityInit(){
        $cityActive = array();
        if (file_exists(ALREADY_INSTALL_FILE)) {
            Yii::import('application.modules.apartmentCity.models.ApartmentCity');
            $cityActive = ApartmentCity::getActiveCity();
            if($cityActive === null){
                $cityActive = array();
            }
        }
        return $cityActive;
    }

	static function apTypes(){
		$result = Apartment::getApTypes();

		$types = array();
		if(in_array(Apartment::PRICE_PER_DAY, $result)){
			$types[Apartment::PRICE_PER_DAY] = Yii::t('common', 'rent by the day');
		}

		if(in_array(Apartment::PRICE_PER_HOUR, $result)){
			$types[Apartment::PRICE_PER_HOUR] = Yii::t('common', 'rent by the hour');
		}

		if(in_array(Apartment::PRICE_PER_MONTH, $result)){
			$types[Apartment::PRICE_PER_MONTH] = Yii::t('common', 'rent by the month');
		}

		if(in_array(Apartment::PRICE_PER_WEEK, $result)){
			$types[Apartment::PRICE_PER_WEEK] = Yii::t('common', 'rent by the week');
		}

		if(in_array(Apartment::PRICE_SALE, $result)){
			$types[Apartment::PRICE_SALE] = Yii::t('common', 'sale');
		}

		$return['propertyType'] = $types;

		$return['currencyName'] = array('', Yii::t('common', 'rub'), Yii::t('common', 'rub/hour'), Yii::t('common', 'rub/day'), Yii::t('common', 'rub/week'), Yii::t('common', 'rub/month'));
		if (issetModule('selecttoslider') && param('usePriceSlider') == 1) {
			$return['currencyTitle'] = array('', Yii::t('common', 'Price range').':', Yii::t('common', 'Price range').':', Yii::t('common', 'Price range').':', Yii::t('common', 'Price range').':', Yii::t('common', 'Price range').':');	
		}
		else {
			$return['currencyTitle'] = array('', Yii::t('common', 'Payment to'), Yii::t('common', 'Fee up to'), Yii::t('common', 'Fee up to'), Yii::t('common', 'Fee up to'), Yii::t('common', 'Fee up to'));
		}
		
		return $return;
	}

}