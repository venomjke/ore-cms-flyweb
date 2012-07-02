<?php
	if($isGuest){
		?>
		<div class="row">
			<div class="full-multicolumn-first">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			<div class="full-multicolumn-second">
				<?php echo $form->labelEx($model,'phone'); ?>
				<?php echo $form->textField($model,'phone'); ?>
				<?php echo $form->error($model,'phone'); ?>
			</div>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'useremail'); ?>
			<?php echo $form->textField($model,'useremail'); ?>
			<?php echo $form->error($model,'useremail'); ?>
		</div>
		<?php
	}
?>

<?php if ($isSimpleForm) { echo '<div id="rent_form">'; } ?>

<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'date_start'); ?>

		<?php

		if(!$model->date_start){
			//$model->date_start = Yii::app()->dateFormatter->format($dateFormat, time());
			$model->date_start = Yii::app()->dateFormatter->formatDateTime(time(), 'medium', null);
			if(Yii::app()->language == 'en'){
				$model->date_start = date('m/d/Y');
			}
		}


		$this->widget('application.extensions.FJuiDatePicker', array(
			'model'=>$model,
			'attribute'=>'date_start',
			'range' => 'eval_period',
			'language' => Yii::app()->language,

			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>Booking::getJsDateFormat(),
				'minDate'=>'new Date()',
			),
		)); ?>
		<?php echo $form->error($model,'date_start'); ?>
	</div>
	<div class="full-multicolumn-second">
		<?php echo $form->labelEx($model,'time_in'); ?>
		<?php echo $form->dropDownList($model,'time_in', $this->getTimesIn(), array('class' => 'width150')); ?>
		<?php echo $form->error($model,'time_in'); ?>
	</div>
</div>
<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'date_end'); ?>
		<?php
		/*if(!$model->date_end){
			$model->date_end = Yii::app()->dateFormatter->formatDateTime(time()+60*60*24, 'medium', null);
		}*/
		$this->widget('application.extensions.FJuiDatePicker', array(
			'model'=>$model,
			'attribute'=>'date_end',
			'range' => 'eval_period',
			'language' => Yii::app()->language,

			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>Booking::getJsDateFormat(),
			),
			));

		?>
		<?php echo $form->error($model,'date_end'); ?>
	</div>
	<div class="full-multicolumn-second">
		<?php echo $form->labelEx($model,'time_out'); ?>
		<?php echo $form->dropDownList($model,'time_out', $this->getTimesOut(), array('class' => 'width150')); ?>
		<?php echo $form->error($model,'time_out'); ?>
	</div>
</div>

<?php if ($isSimpleForm) { echo '</div>'; } ?>

<div class="row">
	<?php echo $form->labelEx($model,'comment'); ?>
	<?php echo $form->textArea($model,'comment',array('class'=>'width500', 'rows' => '3')); ?>
	<?php echo $form->error($model,'comment'); ?>
</div>