 	<div id="fbguploader" class="fbguploader">
		<div class="uploaderTitle"><?php echo Yii::t('common', 'Upload images'); ?></div>

		<?php if($max != '-1'):?>
			<div class="maxFiles">Max:</div><div id="limitFiles" class="limitFiles"><?php echo $max ;?></div>
			<hr />
		<?php endif;?>

		<div class="form">
			<?php echo CHtml::beginForm($this->uploaderConfig['action'], 'post', array('enctype'=>'multipart/form-data'));?>   
			<?php $this->widget('CMultiFileUpload', 
						array(
							'name'=>'uploader',
							'max'=>$max,
							'accept'=>$this->uploaderConfig['accept'],
							'duplicate'=>  Yii::t('common', 'Duplicate of image'), //Yii::t('app', $this->uploaderConfig['duplicate']),
							'denied'=>  Yii::t('common', 'Incorrect image type'), //Yii::t('app', $this->uploaderConfig['denied']),
							'remove'=>'<img src="'.$this->assetUrl.$this->uploaderConfig['remove'].'" height="16" width="16" alt="x" />',
							'selected'=>'ai ales o poze'
						)
				);?>
			<div class="row"><?php echo CHtml::submitButton(Yii::t('common', $this->uploaderConfig['submit']));?></div>
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>


