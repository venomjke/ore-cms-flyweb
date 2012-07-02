<?php

$this->menu=array(
	array('label'=>tt('Add value', 'windowto'), 'url'=>array('create')),
);

$this->adminTitle = tt('Manage reference', 'windowto');

$this->widget('CustomGridView', array(
	'id'=>'special-offer-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title_ru',
		array(
			'deleteConfirmation' => tt('Are you sure you want to delete this value?', 'windowto'),
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
