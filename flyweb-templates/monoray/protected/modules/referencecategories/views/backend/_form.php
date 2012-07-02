<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->modelName.'-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="full-multicolumn-first">
			<?php echo $form->labelEx($model,'title_ru', array('class' => 'ru-flag-label')); ?>
			<?php echo $form->textField($model,'title_ru',array('class'=>'width300','maxlength'=>255)); ?>
			<?php echo $form->error($model,'title_ru'); ?>
		</div>
	</div>
	<div class="clear"></div>
	<div class="row">
		<?php echo $form->labelEx($model,'style'); ?>
		<?php echo $form->dropDownList($model,'style', $model->getStyles(), array('class' => 'width150')); ?>
		<?php echo $form->error($model,'style'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->