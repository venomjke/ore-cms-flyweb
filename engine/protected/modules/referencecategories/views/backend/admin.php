<?php
$this->breadcrumbs=array(
	Yii::t('common', 'References') => array('/site/viewreferences'),
	tt('Manage reference categories')
);

$this->menu=array(
	array('label'=>tt('Add reference category'), 'url'=>array('/referencecategories/backend/main/create')),
);

$this->adminTitle = tt('Manage reference categories');

Yii::app()->clientScript->registerScriptFile(
	Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.referencecategories.assets').'/ajaxMoveRequest.js')
);
?>

<?php $this->widget('CustomGridView', array(
	'id'=>'reference-categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'title_ru',
			'sortable' => false,
			//'filter' => false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{up}{down}{update}{delete}',
			'deleteConfirmation' => tt('Are you sure you want to delete this category?'),
			'htmlOptions' => array('class'=>'infopages_buttons_column'),
			'buttons' => array(
				'up' => array(
					'label' => tt('Move category item up'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/up.gif'
					),
					'url'=>'Yii::app()->createUrl("/referencecategories/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
					'options' => array('class'=>'infopages_arrow_image_up'),
					'visible' => '$data->sorter > 1',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-categories-grid'); return false;}",
				),
				'down' => array(
					'label' => tt('Move category item down'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/down.gif'
					),
					'url'=>'Yii::app()->createUrl("/referencecategories/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
					'options' => array('class'=>'infopages_arrow_image_down'),
					'visible' => '$data->sorter < "'.$maxSorter.'"',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-categories-grid'); return false;}",
				),
			),
		),
	),
)); ?>
