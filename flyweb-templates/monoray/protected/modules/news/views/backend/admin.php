<?php
$this->pageTitle=Yii::app()->name . ' - ' . NewsModule::t('Manage news');


$this->menu = array(
	array('label' => NewsModule::t('Add news'), 'url' => array('create')),
);
$this->adminTitle = NewsModule::t('Manage news');
?>

<?php $this->widget('CustomGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), $data->url)'
		),
		array(
			'name'=>'dateCreated',
			'type'=>'raw',
			'filter'=>false,
			'htmlOptions' => array('style' => 'width:130px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'deleteConfirmation' => tt('Are you sure you want to delete this news?', 'news'),
			'viewButtonUrl' => '$data->url',
		),
	),
)); ?>