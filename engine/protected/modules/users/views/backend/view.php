<?php
$this->breadcrumbs=array(
	Yii::t('common', 'User managment') => array('admin'),
	$model->email.($model->username != '' ? ' ('.$model->username.')' : ''),
);

$this->menu=array(
	/*array('label'=>Yii::t('common', 'User managment'), 'url'=>array('admin')),
	array('label'=>tt('Add user'), 'url'=>array('create')),
	array('label'=>tt('Edit user'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>tt('Delete user'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
		'confirm'=>tt('Are you sure you want to delete this user?'))),*/
	array('label'=>tt('Add user'), 'url'=>array('/users/backend/main/create')),
);
$model->scenario = 'backend';

$this->adminTitle = $model->email.($model->username != '' ? ' ('.$model->username.')' : '');
?>

<div class="view">
	<strong><?php echo CHtml::encode($model->getAttributeLabel('username')); ?>:</strong>
	<?php echo CHtml::encode($model->username); ?>
	<br />

	<strong><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>:</strong>
	<?php echo CHtml::encode($model->email); ?>
	<br />

	<strong><?php echo CHtml::encode($model->getAttributeLabel('phone')); ?>:</strong>
	<?php echo CHtml::encode($model->phone); ?>
	<br />
</div>
