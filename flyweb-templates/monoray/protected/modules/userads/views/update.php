<h1>Редактирование объявления</h1>
<?php
$this->widget('zii.widgets.CMenu', array(
	'items' => array(
		array('label' => 'Управление объявлениями', 'url'=>array('index')),
		array('label' => 'Добавить объявление', 'url'=>array('create')),
		array(
			'label' => 'Удалить объявление',
			'url'=>'#',
			'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить это объявление?')
		),
	)
));

if(isset($show) && $show){
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/scrollto.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('scroll-to','
			scrollto("'.CHtml::encode($show).'");
		',CClientScript::POS_READY
	);
}

$model->metroStations = $model->getMetroStations();
$this->renderPartial('_form',array(
		'model'=>$model,
		'categories' => $categories,
));

