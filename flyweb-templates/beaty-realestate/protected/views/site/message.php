<?php
if ($breadcrumb) {
	$this->breadcrumbs=array(
		$breadcrumb,
	);
}
?>
 
<h2><?php echo CHtml::encode($messageTitle); ?></h2>

<div class="row">
    <p><?php echo CHtml::encode($messageText); ?></p>
</div>