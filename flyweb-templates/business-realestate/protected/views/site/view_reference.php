<?php
	$this->breadcrumbs=array(
		Yii::t('common', 'References'),
	);

	$this->menu = array(
		array('label' => Yii::t('module_referencecategories', 'Categories of references'), 'url' => array('/referencecategories/backend/main/admin')),
		array('label' => Yii::t('module_referencevalues', 'Values of references'), 'url' => array('/referencevalues/backend/main/admin')),
		array('label' => Yii::t('module_windowto', 'Reference (window to..)'), 'url' => array('/windowto/backend/main/admin')),
		
		array('label' => Yii::t('common', 'References "Check-in"'), 'url'=>array('/timesin/backend/main/admin')),
		array('label' => Yii::t('common', 'References "Check-out"'), 'url'=>array('/timesout/backend/main/admin')),
		array(
			'label' => Yii::t('module_metrostations', 'Metro stations'), 'url' => array('/metrostations/backend/main/admin'),
			'visible'=>issetModule('metrostations')
		),
        array('label' => Yii::t('module_apartmentObjType', 'Apartment object types'), 'url' => array('/apartmentObjType/backend/main/admin')),
        array('label' => Yii::t('module_apartmentCity', 'Manage apartment city'), 'url' => array('/apartmentCity/backend/main/admin')),
	);

?>