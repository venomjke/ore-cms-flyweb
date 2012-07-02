
<div class="form">

<?php 
	$model->scenario = 'changePass';
	$model->password = '';
	$model->password_repeat = '';
	
	$form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>false,
	));
	echo CHtml::hiddenField('changePassword', '1');
	?>
	<div class="row">&nbsp;</div>
	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

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
        <?php echo CHtml::submitButton(tt('Change')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->