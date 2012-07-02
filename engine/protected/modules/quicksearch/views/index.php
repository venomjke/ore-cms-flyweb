<?php
$this->pageTitle .= ' - '.Yii::t('common', 'Apartment search');
$this->breadcrumbs=array(
	Yii::t('common', 'Apartment search'),
);
?>

<!--<h1><?php // echo Yii::t('common', 'Quick search') ?></h1>-->

<?php $this->widget('application.modules.apartments.components.ApartmentsWidget', array(
	'criteria' => $criteria,
	'count' => $apCount,
)); ?>
