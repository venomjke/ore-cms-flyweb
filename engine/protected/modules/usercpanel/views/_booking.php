<div class="offer width100p" id="booking<?php echo $model->id;?>">
	<div class="offer-photo" align="left">
		<?php
			$img = $model->apartment->getMainThumb();
			if($img){
				echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/uploads/apartments/'.$model->apartment->id.'/mediumthumbs/'.$img.'"
							alt="'.$model->apartment->getStrByLang('title').'"
							title="'.$model->apartment->getStrByLang('title').'" />',
					$model->apartment->getUrl());
			}
		?>
	</div>
	<div class="offer-text width500">
		<div class="apartment-title">
		<?php echo CHtml::link(
						$model->apartment->getStrByLang('title')
						, $model->apartment->getUrl(), array('class' => 'offer')); ?></div>
			<div class="clear"></div>

			<?php if($model->sum_rur){ ?>
			<p class="cost">
					<?php
						if(Yii::app()->language == 'ru'){
							echo Yii::t('common', '{n} RUR|{n} RUR', $model->sum_rur);
						}
					?>
			</p>
			<?php } ?>
			<div class="row">
				<?php
					echo $model->returnStatusHtml();
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

							<?php echo CHtml::link(tt('Decline booking', 'booking'), '#',
								array('onclick' =>
									'declineBooking("'.$model->id.'", "'.Yii::app()->controller->createUrl('/usercpanel/main/declinebooking',
											array('id' => $model->id)).'"); return false;')
							); ?>
							<?php
								if($model->status == Booking::STATUS_WAITPAYMENT){
									echo ' | '.CHtml::link(tt('Pay', 'payment'), array('/payment/main/paymentform', 'id' => $model->id));;
								}
							?>
						</div>
					<?php
				}
			?>
			<?php

				$title = 'title_'.Yii::app()->language;

				echo '<p class="desc">';
				echo Yii::t('common', 'From').' '.$model->getDate($model->date_start).' ('.$model->time_in_value->$title.') ';
				echo Yii::t('common', 'to').' '.$model->getDate($model->date_end).' ('.$model->time_out_value->$title.') ';
				echo '</p>';

				?>
	</div>
</div>
<div class="clear usercpanel-booking-item"></div>

