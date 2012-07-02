<h1>Управление объявлениями</h1>

<?php

$this->widget('zii.widgets.CMenu', array(
	'items' => array(
		array('label' => 'Добавить объявление', 'url'=>array('create')),
	)
));

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
	'id'=>'userads-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'id',
			'headerHtmlOptions' => array(
				'class'=>'apartments_id_column',
			),
		),
		
		array(
			'name' => 'active',
			'type' => 'raw',
			'value' => 'Userads::returnStatusHtml($data, "userads-grid", 0)',
			'headerHtmlOptions' => array(
				'class'=>'userads_status_column',
			),
			'filter' => Apartment::getModerationStatusArray(),
		),
		
		array(
			'name' => 'owner_active',
			'type' => 'raw',
			'value' => 'Userads::returnStatusOwnerActiveHtml($data, "userads-grid", 1)',
			'headerHtmlOptions' => array(
				'class'=>'userads_owner_status_column',
			),
			'filter' => array(
				'0' => 'Неактивные',
				'1' => 'Активные',
			),
		),
        array(
            'name' => 'type',
            'type' => 'raw',
            'value' => 'Apartment::getNameByType($data->type)',
            'filter' => Apartment::getTypesArray(),
        ),

		array(
			'name' => 'title_ru',
			'type' => 'raw',
			'value' => 'CHtml::link(CHtml::encode($data->title_ru),array("/apartments/main/view","id" => $data->id))',
		),
		array(
			'class'=>'CButtonColumn',
			'deleteConfirmation' => 'Вы действительно хотите удалить это объявление?',
			'viewButtonUrl' => "Yii::app()->createUrl('/apartments/main/view', array('id' => \$data->id))",
		),
	),
)); ?>
