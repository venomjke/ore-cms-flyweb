<?php $this->beginContent('//layouts/main'); ?>
	<form id="search-form" action="<?php echo Yii::app()->controller->createUrl('/quicksearch/main/mainsearch');?>" method="get">
	<div class="searchform-back">
		<div class="searchform" align="left">
			<div class="header-form">
				<div class="header-form-line select-num-of-rooms-inner header-small-search">
					<?php
					
						if (isset(Yii::app()->modules['metrostations'])) {
							$this->renderPartial('//site/field-metro-search', array(
								'divClass' => 'small-header-form-line left width450',
								'textClass' => 'width135',
								'fieldClass' => 'width175 search-input-new',
								'minWidth' => '297',
							));
						}
						/*
                        $this->renderPartial('//site/field-city-search', array(
                            'divClass' => 'small-header-form-line left width450',
                            'textClass' => 'width135',
                            'fieldClass' => 'width175 search-input-new',
                            'minWidth' => '297',
                        ));
						*/
						$this->renderPartial('//site/field-floor-search', array(
							'divClass' => 'small-header-form-line floatright width450',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
						));

						$this->renderPartial('//site/field-type-search', array(
							'divClass' => 'small-header-form-line left width450',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
						));
						
						$this->renderPartial('//site/field-square-search', array(
							'divClass' => 'small-header-form-line floatright width450',
							'textClass' => 'width135',
							'fieldClass' => 'width70 search-input-new',
						));

						$this->renderPartial('//site/field-objtype-search', array(
							'divClass' => 'small-header-form-line left width450',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
						));
						
						$this->renderPartial('//site/field-price-search', array(
							'divClass' => 'small-header-form-line floatright width450',
							'textClass' => 'width135',
							'fieldClass' => 'width70 search-input-new',
						));
						
						$this->renderPartial('//site/field-rooms-search', array(
							'divClass' => 'small-header-form-line left width450',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
						));
						
					?>
					<div class="relative">
						<div class="absolute small-btnsrch-position">
							<a href="javascript: void(0);" onclick="$('#search-form').submit();" id="btnleft" class="small-btnsrch"><?php echo Yii::t('common', 'Search'); ?></a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	</form>
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