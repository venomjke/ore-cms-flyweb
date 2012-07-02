<?php
$this->breadcrumbs=array(
	tt('Manage bookings'),
);

$this->adminTitle = tt('Manage bookings');

$this->widget('CustomGridView', array(
	'id'=>'booking-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	//'beforeAjaxUpdate'  => 'function(id,options){options.type = \'POST\';}',
	'afterAjaxUpdate'=>"function(){jQuery('#".CHtml::activeId($model, 'date_start')
		.", #".CHtml::activeId($model, 'date_end')
		.", #".CHtml::activeId($model, 'date_created')
		."').datepicker({'showAnim':'fold','dateFormat':'".$model->getJsDateFormat()."'})}",
	'columns'=>array(
		array(
			'name' => 'id',
			'htmlOptions' => array(
				'class'=>'apartments_id_column',
			),
		),

		array(
			'name' => 'status',
			'type' => 'raw',
			'value' => '$data->returnStatusHtml()',
			'htmlOptions' => array(
				'class'=>'width240',
			),
			'filter' => CHtml::dropDownList('Booking[status]', $model->status, $model->getStatuses()),
		),
		array(
			'name' => 'useremailSearch',
			'type' => 'raw',
			'value' => '$data->user?$data->user->email:""',
			'htmlOptions' => array(
				'class'=>'width120',
			),
			'sortable' => false,
		),
		array(
			'name' => 'sum',
			'type' => 'raw',
			'value' => '$data->getSumLine()',
			'htmlOptions' => array(
				'class'=>'width80',
			),
			'sortable' => false,
			'filter' => false,
		),
		
		array(
			'name' => 'date_start',
			'type' => 'raw',

			'value' => '$data->getDate($data->date_start)',
			'htmlOptions' => array(
				'class'=>'width70',
			),
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'date_start',
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat' => $model->getJsDateFormat(),
				),
			), true),
		),
		array(
			'name' => 'date_end',
			'type' => 'raw',

			'value' => '$data->getDate($data->date_end)',
			'htmlOptions' => array(
				'class'=>'width70',
			),
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'date_end',
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat' => $model->getJsDateFormat(),
				),
			), true),
		),
		array(
			'name' => 'date_created',
			'type' => 'raw',

			'value' => '$data->getDate($data->date_created, 1)',
			'htmlOptions' => array(
				'class'=>'width70',
			),
			//'sortable' => false,
			'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'date_created',
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat' => $model->getJsDateFormat(),
				),
			), true),
		),
		array(
			'name' => 'apartment_id',
			'type' => 'raw',
			'value' => 'CHtml(); $data->getDate($data->date_created, 1)',
			'value' => 'CHtml::link(CHtml::encode($data->apartment_id),array("/apartments/backend/main/view","id" => $data->apartment_id))',
			'htmlOptions' => array(
				'class'=>'width75',
			),
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{delete}',
			'deleteButtonLabel' => tt('Decline booking'),
			'deleteConfirmation' => tt('Are you sure you want to decline this booking?'),
			'buttons' => array(
				'delete' => array(
					'visible' => '$data->status == Booking::STATUS_NEW || $data->status == Booking::STATUS_WAITPAYMENT',
				),
			),
		),
	),
)); ?>
