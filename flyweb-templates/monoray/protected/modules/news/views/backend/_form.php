<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'News-form',
	'enableClientValidation'=>false,
)); ?>
	<p class="note">
		<?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?>
	</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php
			$this->widget('application.modules.editor.EImperaviRedactorWidget',array(
				'model'=>$model,
				'attribute'=>'body',
				'htmlOptions' => array('class' => 'editor_textarea'),
				'options'=>array(
					'toolbar'=>'custom', /*original, classic, mini, */
					'lang' => Yii::app()->language,
					'focus' => false,
				),
			));
		 ?>
		<?php echo $form->error($model,'page_body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Add') : Yii::t('common', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

