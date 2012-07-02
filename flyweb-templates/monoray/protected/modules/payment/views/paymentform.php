<h1><?php echo Yii::t('module_payment','Payment');?></h1>

<p>
	Платеж за бронь <?php 
		echo CHtml::link('#'.$booking->id, array('/booking/main/view', 'id' => $booking->id));
	?> на сумму <?php echo Yii::t('common', '{n} RUR|{n} RUR', $booking->sum_rur); ?>
</p>

<?php
if($paySystems){
	echo CHtml::beginForm(array('/payment/main/processform'));
	
	?>
	<div class="row">
		Оплатить с помощью:
		<?php
			if(count($paySystems) > 1){
				echo CHtml::dropDownList('id', null, CHtml::listData($paySystems, 'id', 'translatedName'),
						array('class' => 'width150', 'onchange' => 'showDescription(this.value);')
				);
			} else {
				echo CHtml::hiddenField('id', reset($paySystems)->id);
				echo '<strong>'.reset($paySystems)->translatedName.'</strong>';
			}
		?>
	</div>
	<br/>
	<div class="row" id="description">
		<?php echo reset($paySystems)->createPayModel()->getDescription(); ?>
	</div>
    <div class="row submit">
        <?php
			echo CHtml::hiddenField('booking', $booking->id);
			echo CHtml::submitButton('Продолжить');
		?>
    </div>
	<?php	echo CHtml::endForm();

	$descriptions = '';
	foreach($paySystems as $model){
		$descriptions .= 'descr['.$model->id.'] = "'.CJavaScript::quote($model->createPayModel()->getDescription()).'";'."\n";
	}

	Yii::app()->clientScript->registerScript('showDescription', '
		var descr = new Array();;
		'.$descriptions.'
		function showDescription(id){
			$("#description").html("");
			if(descr[id]){
				$("#description").html(descr[id]);
			}
		}
	', CClientScript::POS_END);

}