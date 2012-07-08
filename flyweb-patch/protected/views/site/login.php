<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('common','Login');
$this->breadcrumbs=array(
	Yii::t('common','Login')
);
?>

<h1><?php echo Yii::t('common', 'Login'); ?></h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	/*'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),*/
)); ?>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>
		<?php echo $form->errorSummary($model); ?>
	<table>
		<tr> 
			<td> 		<?php echo $form->labelEx($model,'username'); ?> </td>
			<td>		<?php echo $form->textField($model,'username'); ?></td>
		</tr>
		<tr>
			<td> 		<?php echo $form->labelEx($model,'password'); ?> </td>
			<td>		<?php echo $form->passwordField($model,'password'); ?> </td>
		</tr>
		<tr>
			<td>		<?php echo $form->label($model,'rememberMe'); ?>			</td>
			<td>  		<?php echo $form->checkBox($model,'rememberMe'); ?> </td> 
		</tr>
		<tr>
			<td colspan="2" class="buttons"> <?php echo CHtml::submitButton(Yii::t('common', 'Login')); ?> </td>
		</tr>
	</table>


<?php $this->endWidget(); ?>
</div><!-- form -->
