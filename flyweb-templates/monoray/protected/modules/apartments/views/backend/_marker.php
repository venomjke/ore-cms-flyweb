<div class="gmap-marker">
	<div align="center" class="gmap-marker-adlink">
		<?php echo CHtml::link('<strong>'.tt('ID', 'apartments').': '.$model->id.'</strong>, '.CHtml::encode($model->getStrByLang('title')), $model->getUrl()); ?>
	</div>
	<?php
		$img = $model->getMainThumb();
		if($img){
			?>
				<div align="center" class="gmap-marker-img">
					<img src="<?php echo Yii::app()->baseUrl.'/uploads/apartments/'.$model->id.'/thumbs/'.$img; ?>"
						 title="<?php echo CHtml::encode($model->getStrByLang('title')); ?>"
						 alt="<?php echo CHtml::encode($model->getStrByLang('title')); ?>" />
				</div>
			<?php
		}
	?>
	<div align="center" class="gmap-marker-adress">
		<?php echo CHtml::encode($model->getStrByLang('address')); ?>
	</div>
</div>