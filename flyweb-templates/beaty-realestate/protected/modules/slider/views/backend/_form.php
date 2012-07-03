
<div class="form">

<?php
	if($model->isNewRecord){
		$htmlOptions = array('enctype' => 'multipart/form-data');
		$ajaxValidation = false;
	}
	else{
		$htmlOptions = array();
		$ajaxValidation = true;
	}

	$form=$this->beginWidget('CActiveForm', array(
		'id'=>$this->modelName.'-form',
		'enableAjaxValidation'=>$ajaxValidation,
		'htmlOptions'=> $htmlOptions,
	));
	?>


	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

   <?php if($model->isNewRecord): ?>
		<div class="row">
			<?php echo $form->labelEx($model,'path'); ?>
			<?php echo $form->fileField($model,'path'); ?>
			<?php echo $form->error($model,'path'); ?>
		</div>
	<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('style'=>'width:200px')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descr'); ?>
		<?php echo $form->textField($model,'descr',array('style'=>'width:200px;'));?>
		<?php echo $form->error($model,'descr'); ?>
	</div>

	<div class="clear">&nbsp;</div>
	<div class="row buttons">
		<?php echo CHtml::button($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save'), array(
			'onclick' => "$('#Slider-form').submit(); return false;",
		)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->