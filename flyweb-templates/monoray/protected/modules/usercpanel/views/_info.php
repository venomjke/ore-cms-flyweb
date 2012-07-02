
<div class="form">

<?php
	$model->scenario = 'usercpanel';
	$form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">&nbsp;</div>
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
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>15)); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(tt('Change')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->