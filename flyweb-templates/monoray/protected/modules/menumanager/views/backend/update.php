<?php
$this->breadcrumbs=array(
	'Управление пунктами меню' => array('admin'),
);

$this->menu=array(

	array('label' => 'Управление пунктами меню', 'url'=>array('admin')),
	array('label'=>'Добавить пункт меню', 'url'=>array('create')),

	/*array('label'=>tt('Add station'), 'url'=>array('create')),*/
	array('label'=> 'Удалить пункт меню', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=> 'Вы действительно хотите удалить этот пункт меню?')),
	//array('label'=>tt('Add station'), 'url'=>array('/metrostations/backend/main/create')),
);
$this->adminTitle = 'Редактирование: '.$model->title;
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>