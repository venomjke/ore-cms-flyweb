
<h1><?php echo tt("Change admin password"); ?></h1>
<div class="form">

<?php 
	$model->scenario = 'changeAdminPass';
	$model->password = '';
	$model->password_repeat = '';
	
	$form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>false,
	));
	?>
	<div class="row">&nbsp;</div>
	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'old_password'); ?>
        <?php echo $form->passwordField($model,'old_password',array('size'=>20,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'old_password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>20,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password_repeat'); ?>
        <?php echo $form->passwordField($model,'password_repeat',array('size'=>20,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'password_repeat'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('module_usercpanel', 'Change')); ?>
    </div>

<?php $this->endWidget(); ?>

</div>