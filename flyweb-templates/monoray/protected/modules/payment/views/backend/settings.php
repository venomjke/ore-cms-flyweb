<?php

$this->adminTitle = Yii::t('module_payment','Payment System Settings');

$model->payModel->printInfo();

?>
<div class="form">

<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo CHtml::errorSummary($model); ?>

	<?php
		$this->renderPartial('paymentsystems/'.$model->viewName, array('model' => $model->payModel));
	?>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'status'); ?>
		<?php echo CHtml::activeDropDownList($model,'status',$this->getStatusOptions()); ?>
		<?php echo CHtml::error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('common', 'Save')); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->