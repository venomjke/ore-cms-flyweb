	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'login'); ?>
		<?php echo CHtml::activeTextField($model,'login',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo CHtml::error($model,'login'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'password1'); ?>
		<?php echo CHtml::activeTextField($model,'password1',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo CHtml::error($model,'password1'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'password2'); ?>
		<?php echo CHtml::activeTextField($model,'password2',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo CHtml::error($model,'password2'); ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'incCurrLabel'); ?>
		<?php echo CHtml::activeTextField($model,'incCurrLabel',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo CHtml::error($model,'incCurrLabel'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'text'); ?>
		<?php
			$this->widget('application.modules.editor.EImperaviRedactorWidget',array(
				'name' => 'Robokassa[text]',
				'value' => $model->text,

				'htmlOptions' => array('class' => 'editor_textarea'),

				'options'=>array(
					'toolbar'=>'custom', /*original, classic, mini, */
					'lang' => Yii::app()->language,
					'focus' => false,
				),
			));
		 ?>
		<?php echo CHtml::error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'mode'); ?>
		<?php echo CHtml::activeDropDownList($model,'mode',$this->getModeOptions()); ?>
		<?php echo CHtml::error($model,'mode'); ?>
	</div>
