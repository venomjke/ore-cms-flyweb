<?php
$this->breadcrumbs=array(
	Yii::t('common', 'References') => array('/site/viewreferences'),
	tt('Manage metro stations')=>array('admin'),
	tt('Add station'),
);

$this->menu=array(
	array('label'=>tt('Add station'), 'url'=>array('/metrostations/backend/main/create')),
	//array('label'=>tt('Manage stations'), 'url'=>array('admin')),
	//array('label'=>tt('Add station'), 'url'=>array('create')),
	//array('label'=>tt('Delete station'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>tt('Are you sure you want to delete this station?'))),
);

$this->adminTitle = tt('Edit station:'); echo ' '.$model->stationTitle();
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>