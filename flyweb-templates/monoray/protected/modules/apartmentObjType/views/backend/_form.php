<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->modelName.'-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="full-multicolumn-first">
			<?php echo $form->labelEx($model,'name', array('class' => 'ru-flag-label')); ?>
			<?php echo $form->textField($model,'name',array('class'=>'width300','maxlength'=>255)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>
	<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->