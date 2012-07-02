<?php

$this->breadcrumbs=array(
	tt("FAQ")=>array('index'),
	tt("Manage FAQ"),
);

$this->menu=array(
	array('label'=>tt("Add FAQ"), 'url'=>array('/articles/backend/main/create')),
);
$this->adminTitle = tt('Manage FAQ');

Yii::app()->clientScript->registerScript('ajaxSetStatus', "
		function ajaxSetStatus(elem, id){
			$.ajax({
				url: $(elem).attr('href'),
				success: function(){
					$('#'+id).yiiGridView.update(id);
				}
			});
		}
	",
    CClientScript::POS_HEAD);

$this->widget('CustomGridView', array(
	'id'=>'article-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'active',
			'type' => 'raw',
			'value' => 'Yii::app()->controller->returnStatusHtml($data, "article-grid", 1)',
			'htmlOptions' => array('class'=>'infopages_status_column'),
			'filter' => false,
			'sortable' => false,
		),
		array (
			'name' => 'page_title',
			'htmlOptions' => array('class'=>'width120'),
			'sortable' => false,
			'type' => 'raw',
			'value' => 'CHtml::link(CHtml::encode($data->page_title),array("/articles/backend/main/view","id" => $data->id))',
			
		),
		array (
			'name' => 'page_body',
			'htmlOptions' => array('class'=>'width300'),
			'sortable' => false,
			'type' => 'raw',
			'value' => 'CHtml::decode(truncateText($data->page_body))',
		),
		array (
			'name' => 'date_updated',
			'htmlOptions' => array('class'=>'width70'),
			'sortable' => false,
			'filter' => false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{up}{down}{view}{update}{delete}',
			'deleteConfirmation' => tt('Are you sure you want to delete this FAQ?'),
			'viewButtonUrl' => "Yii::app()->createUrl('/articles/backend/main/view', array('id' => \$data->id))",
			'htmlOptions' => array('class'=>'infopages_buttons_column'),
			'buttons' => array(
				'up' => array(
					'label' => tt('Move menu item up'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/up.gif'
					),
					'url'=>'Yii::app()->createUrl("/articles/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
					'options' => array('class'=>'infopages_arrow_image_up'),
					'visible' => '$data->sorter > 1',
				),
				'down' => array(
					'label' => tt('Move menu item down'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/down.gif'
					),
					'url'=>'Yii::app()->createUrl("/articles/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
					'options' => array('class'=>'infopages_arrow_image_down'),
					'visible' => '$data->sorter < "'.$maxSorter.'"',
				),
			),
		),
	),
)); ?>
