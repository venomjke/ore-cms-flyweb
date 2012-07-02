<div class="form" id="booking<?php echo $model->id; ?>">
	<div class="row">
		<?php
			echo '<strong>'.tt('Status', 'booking').':</strong> '.$model->returnStatusHtml();
			if($model->date_end < date('Y-m-d') && ($model->status == Booking::STATUS_NEW || $model->status == Booking::STATUS_WAITPAYMENT)){
				echo ' ('.tt('Check-out date is expired', 'usercpanel').') ';
			}
		?>
	</div>
	<?php
		if(($model->status == Booking::STATUS_NEW || $model->status == Booking::STATUS_WAITPAYMENT) && $model->date_end >= date('Y-m-d') ){
			?>
				<div class="row">
					<strong><?php echo tt('Actions', 'usercpanel'); ?>:</strong>
					<?php /*echo CHtml::ajaxLink(tt('Decline booking', 'booking'), array('declinebooking', 'id' => $model->id),
						array('replace' => '#booking'.$model->id),
						array('onclick' => 'return confirm("'.tt('Are you sure you want to decline this booking?', 'usercpanel').'");')
					);*/ ?>
					<?php echo CHtml::link(tt('Decline booking', 'booking'), '#',
						array('onclick' =>
							'declineBooking("'.$model->id.'", "'.Yii::app()->controller->createUrl('declinebooking', array('id' => $model->id)).'"); return false;')
					); ?>

				</div>
			<?php
		}
	?>
	
	<?php
		if($model->status != Booking::STATUS_NEW && ($model->sum_rur || $model->sum_usd) ){
			?>
				<div class="row">
					<strong><?php echo tt('Booking price', 'usercpanel'); ?>:</strong>
					<?php
						if($model->sum_rur){
							echo Yii::t('common', '{n} RUR|{n} RUR', $model->sum_rur);
							if($model->sum_usd){
								echo ' '.Yii::t('common', 'or').' ';
							}
						}
						if($model->sum_usd){
							echo Yii::t('common', '{n} USD|{n} USD', $model->sum_usd);
						}
					?>
				</div>
			<?php
		}
	?>

	<div class="row">
		<strong><?php echo tt('Apartment ID', 'booking'); ?>:</strong> <?php echo CHtml::link(CHtml::encode($model->apartment_id),
				array("/apartments/backend/main/view","id" => $model->apartment_id));  ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Check-in date', 'booking'); ?>:</strong> <?php echo $model->getDate($model->date_start); ?>
	</div>
	<div class="row">
		<strong><?php echo tt('Check-out date', 'booking'); ?>:</strong> <?php echo $model->getDate($model->date_end); ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Booking creation date', 'booking'); ?>:</strong> <?php echo $model->getDate($model->date_created, true); ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Check-in time', 'booking'); ?>:</strong> <?php $title = 'title_'.Yii::app()->language; echo $model->time_in_value->$title; ?>
	</div>
	<div class="row">
		<strong><?php echo tt('Check-out time', 'booking'); ?>:</strong> <?php echo $model->time_out_value->$title; ?>
	</div>
	<div class="row">&nbsp;</div>
</div>

<?php
Yii::app()->clientScript->registerScript('updatebooking', '
	function declineBooking(id, url){
		var confirm_msg = "'.tt('Are you sure you want to decline this booking?', 'usercpanel').'";
		if(confirm(confirm_msg)){
			$.ajax({
				url: url,
				success: function(result){
					$("#booking"+id).html(result);
				}
			});
		}
		return false;
	}
', CClientScript::POS_END);


?>