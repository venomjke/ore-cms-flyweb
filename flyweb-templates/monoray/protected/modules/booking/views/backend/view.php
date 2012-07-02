<?php
$this->breadcrumbs=array(
	tt('Manage bookings')=>array('admin'),
	tt('View booking'),
);

$this->menu=array(
	array('label'=>tt('Manage bookings'), 'url'=>array('admin')),
);

if($model->status == Booking::STATUS_NEW || $model->status == Booking::STATUS_WAITPAYMENT){
	$this->menu[] = array('label' => tt('Decline booking'), 'url' => '#',
							'url'=>'#',
							'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
								'confirm'=> tt('Are you sure you want to decline this booking?')
							)
					);
}

$this->adminTitle = tt('View booking');
?>

<div class="form">
	<?php echo '<strong>'.tt('ID', 'apartments').':</strong> '.$model->id; ?><br />
	<?php echo '<strong>'.tt('Status').':</strong> '.$model->returnStatusHtml(); ?><br />
	<?php
		if($model->status == Booking::STATUS_NEW){
			?>
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>$this->modelName.'-form',
					'enableAjaxValidation'=>false,
				)); ?>
				<div class="form">&nbsp;</div>
				<p class="hint"><?php echo tt('Fill following form for booking approve.'); ?></p>
				<div class="row">
					<?php echo $form->labelEx($model,'sum_rur'); ?>
					<?php echo $form->textField($model,'sum_rur'); ?>
					<?php echo $form->error($model,'sum_rur'); ?>
				</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton(Yii::t('common', 'Save')); ?>
				</div>
				<?php $this->endWidget(); ?>
				<div class="form">&nbsp;</div>
			<?php
		}
	?>

	<?php
		if($model->status != Booking::STATUS_NEW){
	?>
		<div class="row">
			<strong><?php echo tt('Booking price (RUR)'); ?>:</strong> <?php echo $model->sum_rur; ?>
		</div>
	<?php
		}
	?>

	<?php
		if($model->user->username){
	?>
		<div class="row">
			<strong><?php echo tt('User name'); ?>:</strong> <?php echo CHtml::encode($model->user->username); ?>
		</div>
	<?php
		}
	?>
	<div class="row">
		<strong><?php echo tt('User e-mail'); ?>:</strong> <?php echo  CHtml::link(CHtml::encode($model->user->email), 'mailto: '.$model->user->email); ?>
	</div>
	<?php 
		if($model->comment){
	?>
		<div class="row">
			<strong><?php echo tt('User comment'); ?>:</strong> <?php echo CHtml::encode($model->comment); ?>
		</div>
	<?php
		}
	?>
				
	<div class="row">
		<strong><?php echo tt('Apartment ID'); ?>:</strong> <?php echo CHtml::link(CHtml::encode($model->apartment_id),
				array("/apartments/backend/main/view","id" => $model->apartment_id));  ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Check-in date'); ?>:</strong> <?php echo $model->date_start; ?>
	</div>
	<div class="row">
		<strong><?php echo tt('Check-out date'); ?>:</strong> <?php echo $model->date_end; ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Booking creation date'); ?>:</strong> <?php echo $model->dateCreated; ?>
	</div>

	<div class="row">
		<strong><?php echo tt('Check-in time'); ?>:</strong> <?php $title = 'title_'.Yii::app()->language; echo $model->time_in_value->$title; ?>
	</div>
	<div class="row">
		<strong><?php echo tt('Check-out time'); ?>:</strong> <?php echo $model->time_out_value->$title; ?>
	</div>


</div>



