<?php
$this->breadcrumbs=array(
	Yii::t('common', 'User managment'),
);

$this->menu=array(
	array('label'=>tt('Add user'), 'url'=>array('/users/backend/main/create')),
);

$this->adminTitle = Yii::t('common', 'User managment');

$this->widget('CustomGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'username',
			'header' => tt('User name'),
		),
		array(
			'name' => 'active',
			'header' => tt('Status'),
			'type' => 'raw',
			'value' => 'Yii::app()->controller->returnStatusHtml($data, "user-grid", 1, 1)',
			'headerHtmlOptions' => array(
				'class'=>'user_status_column',
			),
			'filter' => array(0 => 'Неактивный', 1 => 'Активный'),
		),
		'phone',
		'email',
		array(
			'header' => tt('User\'s bookings'),
			'type' => 'raw',
			'value' => '($data->bookingsCount > 0 ? CHtml::link(CHtml::encode($data->bookingsCount), array("bookings", "id" => $data->id)) : 0)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'deleteConfirmation' => tt('Are you sure you want to delete this user?'),
			'buttons' => array(
				'delete' => array(
					'visible' => '$data->id != 1',
				),
			)
		),
	),
)); ?>
