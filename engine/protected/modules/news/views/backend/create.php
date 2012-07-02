<?php
$this->pageTitle=Yii::app()->name . ' - ' . NewsModule::t('Add news');

$this->menu = array(
	array('label' => NewsModule::t('Add news'), 'url' => array('create')),
);

$this->adminTitle = NewsModule::t('Add news');
?>

<?php echo $this->renderPartial('/backend/_form', array('model'=>$model)); ?>