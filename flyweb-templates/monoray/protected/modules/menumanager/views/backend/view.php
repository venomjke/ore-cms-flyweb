<?php

$this->menu = array(
	array('label' => 'Управление пунктами меню', 'url'=>array('admin')),
	array('label' => 'Добавить пункт меню', 'url'=>array('create')),
	array(
		'label' => 'Редактировать',
		'url'=>array('update', 'id' => $model->id)
	),
	array(
		'label'=> 'Удалить',
		'url'=>'#',
		'linkOptions'=>array(
			'submit'=>array(
				'delete',
				'id' => $model->id
			),
			'confirm' => 'Вы действительно хотите удалить эту страницу?'
		)
	),
);

$this->renderPartial('../view', array(
	'model' => $model
));
