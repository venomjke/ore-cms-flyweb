<?php
$this->breadcrumbs=array(
	Yii::t('common', 'References') => array('/site/viewreferences'),
	tt('Manage metro stations'),
);

$this->menu=array(
	array('label'=>tt('Add station'), 'url'=>array('/metrostations/backend/main/create')),
);

$this->adminTitle = tt('Manage metro stations', 'metrostations');

$this->widget('CustomGridView', array(
	'id'=>'metro-station-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name_ru',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'deleteConfirmation' => tt('Are you sure you want to delete this station?'),
		),
	),
)); ?>
