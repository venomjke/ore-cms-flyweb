<div class='gridview-control-line'>
	<?php
		echo CHtml::beginForm($this->createUrl($url), 'post', array('id'=>'itemsSelected-form'));
	?>
	<img alt="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/arrow_ltr.png"/>
	<?php
		echo Yii::t('common', 'With selected').': ';
		echo CHtml::DropDownList('workWithItemsSelected', $model->WorkItemsSelected, $options).' ';

		echo CHtml::submitButton(
			Yii::t('common', 'Do'),
			array(
				'onclick' => "
					if (confirm('".Yii::t('common', 'You are sure?')."')) {
						$('#itemsSelected-form input[name=\"itemsSelected[]\"]').remove();
						$('#".$id." input[name=\"itemsSelected[]\"]:checked').each(function(){
							$('#itemsSelected-form').append('<input type=\"hidden\" name=\"itemsSelected[]\" value=\"' + $(this).val() + '\" />');
						});
						$.ajax({
							type: 'post',
							url: '".$this->createUrl($url)."',
							data: $('#itemsSelected-form').serialize(),
							success: function (html) {
								$.fn.yiiGridView.update('".$id."');
							},
						});
					}
					return false;
				",
			)
		);
	echo CHtml::endForm(); ?>
</div>