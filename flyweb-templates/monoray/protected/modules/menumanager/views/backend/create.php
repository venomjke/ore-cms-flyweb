<?php

$this->breadcrumbs=array(
	'Управление пунктами меню' => array('admin'),
);

$this->menu=array(
	array('label'=>'Управление пунктами меню', 'url'=>array('admin')),
);

$this->renderPartial('_form', array('model'=>$model));
