<div class="form">
	<?php
		$form=$this->beginWidget('CActiveForm', array(
		'action' => Yii::app()->controller->createUrl('/site/register'),
		'id'=>'user-register-form',
		'enableAjaxValidation'=>false,
	)); ?>
		
	<h2><?php echo Yii::t('common', 'Join now'); ?></h2>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'verifyCode');
		$this->widget('CCaptcha', array('captchaAction' => '/site/captcha', 'buttonOptions' => array('style' => 'display:block;'))); ?><br/>
		<?php echo $form->textField($model, 'verifyCode');?><br/>
		<?php echo $form->error($model, 'verifyCode');?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton(Yii::t('common', 'Registration')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>