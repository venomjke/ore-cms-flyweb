<?php

$urls = array(
	Apartment::TYPE_RENT => $this->createUrl('/userads/main/'.$this->action->id,
		array('id' => $model->isNewRecord? '': $model->id, 'type' => Apartment::TYPE_RENT)),
	Apartment::TYPE_SALE => $this->createUrl('/userads/main/'.$this->action->id,
			array('id' => $model->isNewRecord? '': $model->id, 'type' => Apartment::TYPE_SALE)),
);

Yii::app()->clientScript->registerScript('redirectType', "
    $(document).ready(function() {
		$('#ap_type').live('change', function() {
			var types = ".CJavaScript::encode($urls).";
		    var type = $('#ap_type :selected').val();
		    location.href=types[type];
        });
    });
	",
    CClientScript::POS_HEAD);
?>

<div class="form">

<?php
	if(!$model->isNewRecord){
		$htmlOptions = array('enctype' => 'multipart/form-data');
		$ajaxValidation = true;
	}
	else{
		$htmlOptions = array();
		$ajaxValidation = false;
	}

	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'Apartment-form',
		'enableAjaxValidation'=>$ajaxValidation,
		'htmlOptions'=> $htmlOptions,
	));
	
	$this->renderPartial('//../modules/apartments/views/backend/__form',array(
			'model'=>$model,
			'categories' => $categories,
			'form' => $form,
	));
	?>
	<div class="clear">&nbsp;</div>
	<div class="row buttons">
		<?php echo CHtml::button($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save'), array(
			'onclick' => "$('#Apartment-form').submit(); return false;",
		)); ?>
	</div>
	<?
	$this->endWidget();
	?>

</div><!-- form -->

<?php
	Yii::app()->clientScript->registerScript('show-special', '
		//special-calendar
		if(!$("#Apartment_is_special_offer").is(":checked")){
			$(".special-calendar").hide();
		}
		$("#Apartment_is_special_offer").bind("change", function(){
			if($(this).is(":checked")){
				$(".special-calendar").show();
			} else {
				$(".special-calendar").hide();
			}
		});
	', CClientScript::POS_READY);
?>