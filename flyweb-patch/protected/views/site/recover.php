<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('common','Recover password');
$this->breadcrumbs=array(
	Yii::t('common','Recover password')
);
?>

<h1><?php echo Yii::t('common', 'Recover password'); ?></h1>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'recover-form',
		'enableClientValidation'=>false,
		/*'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),*/
	)); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('common', 'Recover')); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
