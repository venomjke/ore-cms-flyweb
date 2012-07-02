<?php
$this->breadcrumbs=array(
	tt('Control panel') => array('index'),
	tt('Booking archive'),
);
?>

<h1><?php echo tt('Booking archive'); ?></h1>

<?php
	echo '<h2>'.tt('Bookings list').'</h2>';
	foreach($model as $item){
		$this->renderPartial('_booking',array(
			'model'=>$item,
		));
	}
	echo '<div class="clear">&nbsp;</div>';
	if(isset($pages) && $pages){
		$this->widget('itemPaginator',array('pages' => $pages, 'header' => ''));
	}


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
