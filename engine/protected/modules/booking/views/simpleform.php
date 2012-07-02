<?php
$this->breadcrumbs=array(
	tt('Booking apartment'),
);

$this->pageTitle = tt('Booking apartment');
?>

<?php if(!Yii::app()->user->hasFlash('success')): ?>

<div class="form min-fancy-width">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action' => Yii::app()->controller->createUrl('/booking/main/mainform'),
		'enableAjaxValidation'=>false,
	)); ?>
		<h2><?php echo tt('Booking apartment'); ?></h2>
		<?php
			if(Yii::app()->user->isGuest){
				echo Yii::t('module_booking', 'Already used our services? Please <a title="Login" href="{n}">login</a>',
					Yii::app()->controller->createUrl('/site/login')).'<br /><br />';
			}
			else{
				if($user){
					if(!$model->username)
						$model->username = $user->username;
					if(!$model->phone)
						$model->phone = $user->phone;
					if(!$model->useremail)
						$model->useremail = $user->email;
				}
			}
		?>
		<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>
		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'type'); ?>
			<?php echo $form->dropDownList($model,'type', $type, array('class' => 'width200', 'id'=>'ap_type', 'onChange' => 'apTypeChange(this)')); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'rooms'); ?>			
			<?php echo $form->dropDownList($model,'rooms', 
				array_merge(
					array(0 => ''),
					range(1, param('moduleApartments_maxRooms', 8))
				)); ?>
			<?php echo $form->error($model,'rooms'); ?>
		</div>

		<?php
			$this->renderPartial('_form', array(
				'model' => $model,
				'form' => $form,
				'isGuest' => Yii::app()->user->isGuest,
				'isSimpleForm' => true,
			));
		?>
		
		<div class="row buttons">
			<?php 
				echo CHtml::hiddenField('isForBuy', 0, array('id' => 'isForBuy'));
				echo CHtml::submitButton(Yii::t('common', 'Send')); 
			?>
		</div>
	<?php $this->endWidget(); ?>
</div>
<?php endif; ?>

<?php
	
	
	Yii::app()->clientScript->registerScript('show-rent-form', '
		var apTypeValue = document.getElementById("ap_type").value;

		if (apTypeValue != '.Apartment::TYPE_RENT.') {
			document.getElementById("rent_form").style.display = "none";
			document.getElementById("isForBuy").value = 1;
		}
		
		function apTypeChange(control) {
			if (control.value == '.Apartment::TYPE_RENT.') {
				document.getElementById("rent_form").style.display = "";
			}
			else {
				document.getElementById("rent_form").style.display = "none";
				document.getElementById("isForBuy").value = 1;
			}
		}
	', CClientScript::POS_END);
?>