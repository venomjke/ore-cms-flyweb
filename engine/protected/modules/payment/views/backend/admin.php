<?php
$this->pageTitle=Yii::app()->name . ' - ' . tt('Manage payments', 'payment');

$this->adminTitle = tt('Manage payments', 'payment');

$this->widget('CustomGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
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
			'filter' => CHtml::dropDownList('Payments[status]', $model->status, $model->getStatuses()),
		),
		array(
			'name' => 'paysystem_name',
			'type' => 'raw',
			'value' => '$data->paysystem->name',
			//'filter' => CHtml::dropDownList('Payments[paysystem]', $data->name, $model->paysystem),
		),
		array(
			'name'=>'order',
			'type'=>'raw',
			'filter'=>false,
			'value'=>'CHtml::link(CHtml::encode($data->order->id), array("/booking/backend/main/view", "id" => $data->order->id))',
			'htmlOptions' => array('style' => 'width:70px;'),
		),
		array(
			'name'=>'sum',
			'type'=>'raw',
			'htmlOptions' => array('style' => 'width:70px;'),
		),
		array(
			'name'=>'date_created',
			'type'=>'raw',
			'filter'=>false,
			'htmlOptions' => array('style' => 'width:130px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{confirm} {delete}',
			'buttons' => array(
				'delete' => array(
					'visible' => '$data->status == Payments::STATUS_WAITPAYMENT',
				),
                'confirm' => array(
                    'visible' => '$data->status == Payments::STATUS_WAITOFFLINE',
					'imageUrl' => Yii::app()->request->baseUrl.'/images/active.png',
					'url'=>'Yii::app()->createUrl("/payment/backend/main/confirm", array("id"=>$data->id))',
                    'label' => 'Подтвердить платеж'
					),
                )
			),
		),
	)
); ?>