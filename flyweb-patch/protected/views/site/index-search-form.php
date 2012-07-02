<form id="search-form" action="<?php echo Yii::app()->controller->createUrl('/quicksearch/main/mainsearch');?>" method="get">
    <div class="searchform-back">
        <div class="searchform-index" align="left">
            <div class="header-form">
                <?php
					if (isset(Yii::app()->modules['metrostations'])) {
						$this->renderPartial('//site/field-metro-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
							'minWidth' => '297',
						));
					}
					/*
                    $this->renderPartial('//site/field-city-search', array(
                        'divClass' => 'header-form-line',
                        'textClass' => 'width135',
                        'fieldClass' => 'width175 search-input-new',
                        'minWidth' => '297',
                    ));
					*/

					$this->renderPartial('//site/field-type-search', array(
						'divClass' => 'header-form-line',
						'textClass' => 'width135',
						'fieldClass' => 'width175 search-input-new',
					));

					$this->renderPartial('//site/field-objtype-search', array(
						'divClass' => 'header-form-line',
						'textClass' => 'width135',
						'fieldClass' => 'width175 search-input-new',
					));
					
					$this->renderPartial('//site/field-rooms-search', array(
						'divClass' => 'header-form-line',
						'textClass' => 'width135',
						'fieldClass' => 'width175 search-input-new',
					));
					
					$this->renderPartial('//site/field-price-search', array(
						'divClass' => 'header-form-line',
						'textClass' => 'width135',
						'fieldClass' => 'width70 search-input-new',
					));
					
					echo '<div id="more-options-form">';
						$this->renderPartial('//site/field-square-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'width135',
							'fieldClass' => 'width70 search-input-new',
						));
					
						$this->renderPartial('//site/field-floor-search', array(
							'divClass' => 'header-form-line',
							'textClass' => 'width135',
							'fieldClass' => 'width175 search-input-new',
						));
					echo '</div>';
					
					Yii::app()->clientScript->registerScript('more-options', '
						$("#more-options-link").click(function(){
							if ($("#more-options-form").is(":hidden")) {
								$("#homeintro").css({"height" : "360"});
								$("#more-options-form").show();
								$("#more-options-link").html("'.Yii::t("common", "Less options").'");
							} else {
								$("#homeintro").css({"height" : "270"});
								$("#more-options-form").hide();
								$("#more-options-link").html("'.Yii::t("common", "More options").'");
							}                          
						});
					', CClientScript::POS_READY);
				?>

                <div class="header-form-line">
					<a href="javascript: void(0);" id="more-options-link"><?php echo Yii::t('common', 'More options'); ?></a>
                    <a href="javascript: void(0);" onclick="$('#search-form').submit();" id="btnleft" class="btnsrch"><?php echo Yii::t('common', 'Search'); ?></a>
                </div>
            </div>
        </div>
    </div>
</form>