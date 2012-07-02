<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->modelName.'-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php Yii::t('common', 'Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'page_title'); ?>
		<?php echo $form->textField($model,'page_title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'page_title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'page_body'); ?>
		<?php
			$this->widget('application.modules.editor.EImperaviRedactorWidget',array(
				'model'=>$model,
				'attribute'=>'page_body',

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
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->