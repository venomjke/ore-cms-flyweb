<?php
$this->breadcrumbs=array(
	Yii::t('common', 'User managment') => array('admin'),
	tt('Add user'),
);

$this->menu=array(
	//array('label'=>Yii::t('common', 'User managment'), 'url'=>array('admin')),
	array('label'=>tt('Add user'), 'url'=>array('/users/backend/main/create')),
);

$this->adminTitle = tt('Add user');
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>