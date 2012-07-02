<div class="row">
	<?php echo CHtml::activeLabelEx($model,'text'); ?>
	<?php
		$this->widget('application.modules.editor.EImperaviRedactorWidget',array(
			'name' => 'Offline[text]',
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
