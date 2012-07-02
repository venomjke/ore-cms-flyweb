<?php
$this->pageTitle .= ' - '.Yii::t('module_specialoffers', 'Special offers');
$this->breadcrumbs=array(
	Yii::t('module_specialoffers', 'Special offers'),
);
?>
<h1><?php echo Yii::t('common', 'Special offers') ?></h1>
<?php
$this->widget('application.modules.apartments.components.ApartmentsWidget', array(
	'criteria' => $criteria,
	'widgetTitle' => '',
));
?>
