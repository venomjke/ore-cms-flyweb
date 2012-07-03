<?php
$this->breadcrumbs=array(
	'Управление слайдером',
);

$this->menu = array(
	array('label'=>'Добавить слайд', 'url'=>array('create')),
);
$this->adminTitle = 'Управление слайдером';

if(Yii::app()->user->hasFlash('mesIecsv')){
	echo "<div class='flash-success'>".Yii::app()->user->getFlash('mesIecsv')."</div>";
}

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
	'id'=>'slider-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(

		array(
			'name' => 'Изображение',
			'type' => 'raw',
			'value' => 'CHtml::image(Yii::app()->request->baseUrl."/uploads/slider/".$data->path,$data->title,array("style"=>"width:200px;height:130px;"))',
			'htmlOptions' => array(
				'style' => 'width:200px;height:130px',
			)
		),		
		array(
			'name' => 'Заголовок',
			'value' => '$data->title'
		),
		array(
			'name' => 'Описание',
			'value' => '$data->descr'
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'deleteConfirmation' => 'Вы действительно хотите удалить слайд?',
			'htmlOptions' => array('class'=>'infopages_buttons_column'),
		),
	),
));

?>
