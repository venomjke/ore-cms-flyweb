<?php
$this->breadcrumbs=array(
	//Yii::t('common', 'objects') => array('/site/viewobjects'),
	tt('Manage apartment object types')
);

$this->menu=array(
	array('label'=>tt('Add object type'), 'url'=>array('/apartmentObjType/backend/main/create')),
);

$this->adminTitle = tt('Manage apartment object types');

Yii::app()->clientScript->registerScriptFile(
	Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.apartmentObjType.assets').'/ajaxMoveRequest.js')
);
?>

<?php $this->widget('CustomGridView', array(
	'id'=>'object-categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'name',
			'sortable' => false,
			//'filter' => false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{up}{down}{update}{delete}',
			'deleteConfirmation' => tt('When you remove the type of real estate, ads with this type will be assigned to the first remaining property type and ads will be inactive.'),
			'htmlOptions' => array('class'=>'infopages_buttons_column'),
			'afterDelete'=>'function(link, success, data){ if (data == 0) {alert("Удалить последний тип нельзя по причине необходимости данного поля в каждом объявлении."); } }',
			'buttons' => array(
				'up' => array(
					'label' => tt('Move object type item up'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/up.gif'
					),
					'url'=>'Yii::app()->createUrl("/apartmentObjType/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
					'options' => array('class'=>'infopages_arrow_image_up'),
					'visible' => '$data->sorter > 1',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'object-categories-grid'); return false;}",
				),
				'down' => array(
					'label' => tt('Move object type item down'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/down.gif'
					),
					'url'=>'Yii::app()->createUrl("/apartmentObjType/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
					'options' => array('class'=>'infopages_arrow_image_down'),
					'visible' => '$data->sorter < "'.$maxSorter.'"',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'object-categories-grid'); return false;}",
				),
			),
		),
	),
)); ?>
