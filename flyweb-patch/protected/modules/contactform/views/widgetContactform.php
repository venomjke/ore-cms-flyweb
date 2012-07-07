<h2><?php echo tt('Contact Us', 'contactform'); ?></h2>
<?php
	Yii::app()->clientScript->registerScriptFile('http://download.skype.com/share/skypebuttons/js/skypeCheck.js', CClientScript::POS_END);
	
	if(!Yii::app()->user->isGuest){
		if(!$model->name)
			$model->name = Yii::app()->user->username;
		if(!$model->phone)
			$model->phone = Yii::app()->user->phone;
		if(!$model->email)
			$model->email = Yii::app()->user->email;
	}
	
	echo '<p>'.tt('Phone', 'contactform').': '.param('adminPhone').',&nbsp;'.tt('Address', 'contactform').': '.param('adminAddress').'</p>';
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>false,
));
?>
	<p>
		<?php echo tt('You can fill out the form below to contact us.', 'contactform'); ?>
	</p>
	
	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

     <table>
     	<tr>
     		<td> <?php echo $form->labelEx($model,'name'); ?> </td>
     		<td> <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?> </td>
     	</tr>
     	<tr>
     		<td> <?php echo $form->labelEx($model,'email'); ?> </td>
     		<td> <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?> </td>
     	</tr>
     	<tr>
     		<td> <?php echo $form->labelEx($model,'phone'); ?> </td>
     		<td> <?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?> </td>
     	</tr>
     	<tr>
     		<td> <?php echo $form->labelEx($model,'body'); ?> </td>
     		<td> <?php echo $form->textArea($model,'body',array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?></td>
     	</tr>

     	<?php
			if (Yii::app()->user->isGuest){
			?>
			<tr>
				<td> <?php echo $form->labelEx($model, 'verifyCode');?> </td>
				<td> <?php
					$cAction = '/menumanager/main/captcha';
					if($this->page == 'index'){
						$cAction = '/site/captcha';
					} elseif ($this->page == 'contactForm'){
						$cAction = '/contactform/main/captcha';
					}
					$this->widget('CCaptcha',
						array('captchaAction' => $cAction, 'buttonOptions' => array('style' => 'display:block;') )
					);?>
					<br/>
					<?php echo $form->textField($model, 'verifyCode');?><br/>
				</td>
			</tr>
			<?php
			}
			?>
		<tr>
			<td colspan="2"> <?php echo CHtml::submitButton(tt('Send message','contactform')); ?> </td>
		</tr> 
     </table>
<?php $this->endWidget(); ?>

</div>
