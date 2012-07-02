<?php
$this->breadcrumbs=array(
	Yii::t('common', 'References') => array('/site/viewreferences'),
	tt('Manage reference (window to..)'),
);

$this->menu=array(
	/*array('label'=>tt('Manage reference (window to..)'), 'url'=>array('index')),
	array('label'=>tt('Add value'), 'url'=>array('create')),*/
	array('label'=>tt('Add value'), 'url'=>array('/windowto/backend/main/create')),
);

$this->adminTitle = tt('Manage reference (window to..)');

$this->widget('CustomGridView', array(
	'id'=>'special-offer-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title_ru',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
