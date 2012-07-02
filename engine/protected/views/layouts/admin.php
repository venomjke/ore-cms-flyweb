<?php $this->beginContent('//layouts/main', array('adminView' => 1)); ?>

<?php
if(Yii::app()->user->hasFlash('mesNewNews')){
	echo "<div class='flash-notice'>".Yii::app()->user->getFlash('mesNewNews')."</div>";
}
?>

    <div class="admin-header-title">
        <?php echo $this->adminTitle; ?>
    </div>

<?php

if ($this->menu) {
    echo '<div class="adminManageLink">';
	$this->widget('zii.widgets.CMenu', array(
		'items'=>$this->menu
	));
    echo '</div>';
	echo '<hr />';	
}
?>

	<div class="main-content-admin">
		<div class="admin-wrapper">
        <div id="statusMsg"></div>
		<?php
			foreach(Yii::app()->user->getFlashes() as $key => $message) {
				if ($key=='error' || $key == 'success' || $key == 'notice'){
					echo "<div class='flash-{$key}'>{$message}</div>";
				}
			}
			echo $content;
		?>
		</div>
	</div>
<?php $this->endContent(); ?>