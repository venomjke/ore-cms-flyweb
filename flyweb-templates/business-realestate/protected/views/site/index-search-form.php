<form id="search-form" action="<?php echo Yii::app()->controller->createUrl('/quicksearch/main/mainsearch');?>" method="get">
	<div class="form">
	 	  <?php $this->renderPartial('//site/field-objtype-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'search',
							'fieldClass' => 'width175 search-input-new spisok',
						));
	 	  		$this->renderPartial('//site/field-type-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'search',
							'fieldClass' => 'width175 search-input-new spisok',
						));
	 	  		if (isset(Yii::app()->modules['metrostations'])) {
						$this->renderPartial('//site/field-metro-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'search',
							'fieldClass' => 'width175 search-input-new',
							'minWidth' => '244',
						));
				}
				$this->renderPartial('//site/field-rooms-search', array(
					'divClass' => 'header-form-line',
					'textClass' => 'search',
					'fieldClass' => 'width175 search-input-new',
				));

				$this->renderPartial('//site/field-price-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'search',
							'fieldClass' => 'width70 search-input-new',
						));
				$this->renderPartial('//site/field-square-search', array(
								'divClass' => 'header-form-line',
								'textClass' => 'search',
								'fieldClass' => 'width70 search-input-new',
							));
				$this->renderPartial('//site/field-floor-search', array(
									'divClass' => 'header-form-line',
									'textClass' => 'search',
									'fieldClass' => 'width175 search-input-new',
								));
			?> 	
	</div>
	<!--<input type="submit" name="button" id="button" value="ПОДОБРАТЬ" /> -->
	<a href="javascript: void(0);" onclick="$('#search-form').submit();" id="btnleft" class="btnsrch"><?php echo Yii::t('common', 'Search'); ?></a>
	<div class="clear"></div>
</form>