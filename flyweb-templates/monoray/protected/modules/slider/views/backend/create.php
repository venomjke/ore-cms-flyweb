<?php
$this->breadcrumbs=array(
	'Управление слайдером' => array('admin'),
	'Добавление слайда',
);

$this->menu = array(
	array('label'=>'Управление слайдами', 'url'=>array('admin'))
);

$this->adminTitle = 'Добавление слайда';
?>

<?php

	$this->renderPartial('_form',array(
			'model'=>$model
		));
?>