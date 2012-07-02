<?php
$this->breadcrumbs=array(
	'Управление пунктами меню',
);

$this->menu = array(
	array('label'=>'Добавить пункт меню', 'url'=>array('create')),
);

$this->adminTitle = 'Управление пунктами меню';

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

Yii::app()->clientScript->registerScriptFile(
	Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.referencecategories.assets').'/ajaxMoveRequest.js')
);

$this->widget('CustomGridView', array(
	'id'=>'apartments-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'active',
			'type' => 'raw',
			'value' => 'Yii::app()->controller->returnStatusHtml($data, "apartments-grid", 1)',
			'headerHtmlOptions' => array(
				'class'=>'apartments_status_column',
			),
			'filter' => false,
			'sortable' => false,
		),

		array(
			'name' => 'title',
			'type' => 'raw',
			'value' => '$data->getTitle()',
			'filter' => false,
			'sortable' => false,
		),
		array(
			'class'=>'CButtonColumn',
			'deleteConfirmation' => 'Вы дейтсвительно хотите удалить этот пункт меню?',
			'template'=>'{up}{down}{view}{update}{delete}',
			'htmlOptions' => array('class'=>'infopages_buttons_column'),

			'buttons' => array(
				'up' => array(
					'label' => 'Переместить элемент выше',
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/up.gif'
					),
					'url'=>'Yii::app()->createUrl("/menumanager/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
					'options' => array('class'=>'infopages_arrow_image_up'),
					'visible' => '$data->sorter > "'.$minSorter.'"',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'apartments-grid'); return false;}",
				),
				'down' => array(
					'label' => 'Переместить элемент ниже',
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/down.gif'
					),
					'url'=>'Yii::app()->createUrl("/menumanager/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
					'options' => array('class'=>'infopages_arrow_image_down'),
					'visible' => '$data->sorter < "'.$maxSorter.'"',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'apartments-grid'); return false;}",
				),
				
				'delete' => array(
					'visible' => '$data->special == 0',
				),
			),
		),
	),
)); ?>
