<?php
$this->breadcrumbs=array(
	'Управление слайдером' => array('admin'),
	'Редактирование слайда',
);

$this->menu = array(
	array('label'=>'Управление слайдами', 'url'=>array('admin')),
	array('label'=>'Добавить слайд', 'url'=>array('create')),
	array('label'=>'Удалить слайд', 'url'=>'#', 
	      'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно желаете удалить слайд?')
		 ),
);

$this->adminTitle = 'Редактирование слайда';
?>

<?php
	$this->renderPartial('_form',array(
			'model'=>$model,
	));
?>