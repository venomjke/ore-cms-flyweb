<?php
$this->breadcrumbs=array(
	Yii::t('module_comments', 'Comments'),
);

$this->adminTitle = Yii::t('module_comments', 'Comments');

$this->widget('CustomGridView', array(
	'id'=>'comment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'name' => 'id',
			'filter' => false,
			'sortable' => false,
        ),
		array(
			'name' => 'active',
			'type' => 'raw',
			'value' => 'Yii::app()->controller->returnStatusHtml($data, "comment-grid")',
			'htmlOptions' => array('class'=>'infopages_status_column'),
			'filter' => false,
			'sortable' => true,
		),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Comment::getUserEmailLink($data)',
            'filter' => true,
            'sortable' => true
        ),
        'body',
        array(
            'name' => 'apartment_id',
            'type' => 'raw',
            'value' => 'CHtml::link($data->apartment->id, $data->apartment->getUrl())',
            'filter' => false,
            'sortable' => true
        ),
		array(
            'name' => 'date_created',
			'filter' => false,
			'sortable' => true,
        ),
		array(
			'class'=>'CButtonColumn',
            'template' => '{update} {delete}',
			'deleteConfirmation' => Yii::t('module_comments', 'Are you sure to delete comment ?', '$data->id'),
			'viewButtonUrl' => '',
		),
	),
));

?>