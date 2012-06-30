<?php $this->beginContent('//layouts/main'); ?>
	<div id="homeheader">
		<div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
				<?php if (issetModule('slider')): {
						$Slider = Slider::model();
						$images = $Slider->getAllImages();
						foreach($images as $image):
						?>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/slider/<?php echo $image->path; ?>" alt="<?php echo $image->title; ?>" <?php echo (!empty($image->title) or !empty($image->descr))?'title="#title-'.$image->id.'"':""; ?> width="500" height="310" />
						<?php
						endforeach;
				} 
				endif;
				?>
			</div>
			<?php if(issetModule('slider')): ?>
			<?php 	foreach($images as $image): ?>
				<?php if(!empty($image->title) or !empty($image->descr)): ?>
					<div style="display:none;" id="title-<?php echo $image->id; ?>"> <?php echo $image->title." ".$image->descr; ?> </div>
				<?php endif;?>
			<?php 	endforeach; ?>
			<?php endif; ?>
        </div>

		<?php
			Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/slider/themes/default/default.css');
			Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/slider/nivo-slider.css');

			Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/slider/jquery.nivo.slider.pack.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScript('slider', '
				$("#slider").nivoSlider({effect: "random"});
			', CClientScript::POS_READY);
		?>
		<div id="homeintro">
            <?php Yii::app()->controller->renderPartial('//site/index-search-form'); ?>
		</div>
	</div>

	<div class="main-content">
		<div class="main-content-wrapper">
			<?php
				foreach(Yii::app()->user->getFlashes() as $key => $message) {
					if ($key=='error' || $key == 'success' || $key == 'notice'){
						echo "<div class='flash-{$key}'>{$message}</div>";
					}
				}
			?>
			<?php echo $content; ?>
		</div>
	</div>
<?php $this->endContent(); ?>