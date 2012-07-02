<?php
$this->pageTitle=Yii::app()->name . ' - ' . ConfigurationModule::t('Manage settings');
$title = 'title_'.Yii::app()->language;
$this->breadcrumbs=array(
	ConfigurationModule::t('Settings')=>array('/configuration/backend/main'),
	ConfigurationModule::t('Update {name}', array('{name}'=>$model->$title)),
);

$this->adminTitle = ConfigurationModule::t('Update param "{name}"', array('{name}'=>$model->$title));
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->modelName.'-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('common', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->