<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->modelName.'-form',
	'enableAjaxValidation'=>false,
));
if(!Yii::app()->user->getState('isAdmin') && !Yii::app()->user->isGuest){
	$model->name = Yii::app()->user->username;
	$model->email = Yii::app()->user->email;
}

?>
	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<table>
		<tr>
			<td>  <?php echo $form->labelEx($model,'name'); ?> </td>
			<td> 		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?> </td>
		</tr>
		<tr>
			<td>		<?php echo $form->labelEx($model,'email'); ?></td>
			<td>		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?></td>
		</tr>
		<tr>
			<td> 		<?php echo $form->labelEx($model,'body'); ?> </td>
			<td>		<?php echo $form->textArea($model,'body',array('rows'=>3, 'cols'=>50, 'class' => 'width240')); ?> </td>
		</tr>
		<tr>
			<td>		<?php echo $form->labelEx($model,'rating'); ?> </td>
			<td> 		<?php $this->widget('CStarRating',array('name'=>'Comment[rating]', 'value'=>$model->rating, 'resetText' => 'Отменить оценку')); ?> </td>
		</tr>
		<?php
			if (Yii::app()->user->isGuest){
			?>
		<tr>
			<td colspan="2">		<?php $this->widget('CCaptcha', array('captchaAction' => '/apartments/main/captcha', 'buttonOptions' => array('style' => 'display:block;') ));?><br/>
 </td>
		</tr>
		<tr>
			<td>
				<?php echo $form->labelEx($model, 'verifyCode');?> 
			</td>
			<td>
				<?php echo $form->textField($model, 'verifyCode');?>
			</td>
		</tr>

			<?php
			}
			?>
		<tr>
			<td colspan="2">		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Add') : Yii::t('common', 'Save')); ?> </td>
		</tr>
	</table>
<?php $this->endWidget(); ?>

</div><!-- form -->