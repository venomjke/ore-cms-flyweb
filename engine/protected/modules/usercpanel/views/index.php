<?php
$this->breadcrumbs=array(
	Yii::t('common', 'Control panel'),
);
?>

<h1><?php echo Yii::t('common', 'Control panel'); ?></h1>

<?php
    if(param('useUserads')){
	    echo CHtml::link('Управление объявлениями',array('/userads/main/index'));
    }
?>

<div class="row">
	<?php
		$errors = $model->getErrors();
		if($errors && (isset($errors['username']) || isset($errors['email']))){
			$display = '';
		}
		else{
			$display = 'display:none;';
		}
	?>
	<?php echo CHtml::link(tt('Change your name, phone or e-mail'),'#', array('class'=>'changeinfo-button')); ?>
	<div class="info-form" style="<?php echo $display; ?>">
	<?php $this->renderPartial('_info',array(
		'model'=>$model,
	)); ?>
	</div>
</div>

<div class="row">
	<?php
		$errors = $model->getErrors();
		if($errors && (isset($errors['password']) || isset($errors['password_repeat']))){
			$display = '';
		}
		else{
			$display = 'display:none;';
		}
	?>

	<?php echo CHtml::link(tt('Change your password'),'#', array('class'=>'changepassword-button')); ?>
	<div class="password-form" style="<?php echo $display; ?>">
	<?php $this->renderPartial('_password',array(
		'model'=>$model,
	)); ?>
	</div>
</div>
<div class="row">
	<?php
		$oldBookingCnt = $this->countOldBookings();
		if($oldBookingCnt){
			echo CHtml::link(tt('View your booking archive').' ('.$oldBookingCnt.')', array('viewarchive'));
		}
	?>
</div>

<?php
$lastBookings = $this->getLastBookings();
if(isset($lastBookings['items']) && $lastBookings['items']){
	echo '<h2>'.tt('Bookings list').'</h2>';
	foreach($lastBookings['items'] as $item){
		$this->renderPartial('_booking',array(
			'model'=>$item,
		));
	}
	echo '<div class="clear">&nbsp;</div>';
	if(isset($lastBookings['pages']) && $lastBookings['pages']){
		$this->widget('itemPaginator',array('pages' => $lastBookings['pages'], 'header' => ''));
	}
}

Yii::app()->clientScript->registerScript('showinfo', '
	$(".changeinfo-button").click(function(){
		$(".info-form").toggle();
		return false;
	});
	$(".changepassword-button").click(function(){
		$(".password-form").toggle();
		return false;
	});
');

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