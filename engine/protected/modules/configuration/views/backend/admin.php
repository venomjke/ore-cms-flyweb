<?php

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

$this->pageTitle=Yii::app()->name . ' - ' . ConfigurationModule::t('Manage settings');
$this->breadcrumbs=array(
	ConfigurationModule::t('Settings'),
);

$this->adminTitle = ConfigurationModule::t('Manage settings');

$this->widget('CustomGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'id'=>'config-table',
	'columns'=>array(
        array(
            'header'=>'Настройки',
            'value' => 'tt($data->section)',
            'filter' => CHtml::dropDownList('section_filter', $currentSection, $this->getSections()),

        ),
		array(
			'name'=>'title_'.Yii::app()->language,
			'type'=>'raw',
			'htmlOptions' => array('class' => 'width250'),
		),
		array(
			'name'=>'value',
            'type'=>'raw',
			'value' => 'ConfigurationModel::getAdminValue($data)',
			'htmlOptions' => array('class' => 'width150'),
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update}',
            'buttons' => array(
                'update' => array(
                    'visible' => 'ConfigurationModel::getVisible($data->type)'
                    )
                )
		),
	),
)); ?>