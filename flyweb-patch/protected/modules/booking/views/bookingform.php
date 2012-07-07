<?php
$this->breadcrumbs=array(
	$apartment->getStrByLang('title') => $apartment->getUrl(),
	tt('Booking apartment'),
);

$this->pageTitle = tt('Booking apartment').' - '.$apartment->getStrByLang('title');
?>

<div class="form min-fancy-width <?php echo $isFancy ? 'max-fancy-width' : ''; ?>">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action' => Yii::app()->controller->createUrl('/booking/main/bookingform', array('id' => $apartment->id)),
		'id'=>$this->modelName.'-form',
		'enableAjaxValidation'=>false,
	)); ?>
		<h2><?php echo tt('Booking apartment').': '.$apartment->getStrByLang('title'); ?></h2>
		<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>
		<?php echo $form->errorSummary($model); ?>

		<?php
			$this->renderPartial('_form', array(
				'model' => $model,
				'form' => $form,
				'isGuest' => $isGuest,
				'isSimpleForm' => false,
				
			));
		?>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('common', 'Send')); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>