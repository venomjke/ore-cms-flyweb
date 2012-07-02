<?php
$this->breadcrumbs=array(
	//Yii::t('common', 'objects') => array('/site/viewobjects'),
	tt('Manage apartment city')
);

$this->menu=array(
	array('label'=>tt('Add city'), 'url'=>array('/apartmentCity/backend/main/create')),
);

$this->adminTitle = tt('Manage apartment city');

Yii::app()->clientScript->registerScriptFile(
	Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.apartmentCity.assets').'/ajaxMoveRequest.js')
);

$this->widget('CustomGridView', array(
	'id'=>'apartment-city-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'class'=>'CCheckBoxColumn',
            'id'=>'itemsSelected',
            'selectableRows' => '2',
            'htmlOptions' => array(
                'class'=>'center',
            ),
        ),
		array(
			'name' => 'name',
			'sortable' => false,
			//'filter' => false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{up}{down}{update}{delete}',
			'deleteConfirmation' => tt('Are you sure you want to delete this city?'),
			'htmlOptions' => array('class'=>'infopages_buttons_column'),
			'buttons' => array(
				'up' => array(
					'label' => tt('Move city item up'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/up.gif'
					),
					'url'=>'Yii::app()->createUrl("/apartmentCity/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
					'options' => array('class'=>'infopages_arrow_image_up'),
					'visible' => '$data->sorter > 1',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'apartment-city-grid'); return false;}",
				),
				'down' => array(
					'label' => tt('Move city item down'),
					'imageUrl' => $url = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('zii.widgets.assets.gridview').'/down.gif'
					),
					'url'=>'Yii::app()->createUrl("/apartmentCity/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
					'options' => array('class'=>'infopages_arrow_image_down'),
					'visible' => '$data->sorter < "'.$maxSorter.'"',
					'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'apartment-city-grid'); return false;}",
				),
			),
            'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
		),
	),
)); ?>

<?php
	$this->renderPartial('//site/admin-select-items', array(
		'url' => '/apartmentCity/backend/main/itemsSelected',
		'id' => 'apartment-city-grid',
		'model' => $model,
		'options' => array(
			'delete' => Yii::t('common', 'Delete')
		),
	));
?>
