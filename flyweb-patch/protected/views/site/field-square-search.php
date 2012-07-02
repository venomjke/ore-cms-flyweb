<div class="<?php echo $divClass; ?>">
		<?php				
			if (issetModule('selecttoslider') && param('useSquareSlider') == 1) {
				?>
				<span class="search"><div class="<?php echo $textClass; ?>"><?php echo Yii::t('common', 'Square range'); ?>:</div> </span>
				<span class="search">
					<?php
					$squareAll = Apartment::model()->getSquareMinMax();

					$squareAll['square_min'] = isset($squareAll['square_min']) ? $squareAll['square_min'] : 0;
					$squareAll['square_max'] = isset($squareAll['square_max']) ? $squareAll['square_max'] : 100;
										
					$step = 5;
					if ($squareAll['square_max'] - $squareAll['square_min'] <= 5) {
						$step = 1;
					}
					
					$squareItems = array_combine(
								range($squareAll['square_min'], $squareAll['square_max'], $step),
								range($squareAll['square_min'], $squareAll['square_max'], $step)
					);
					
					// add last element if step less
					if (max($squareItems) != $squareAll["square_max"]) {
						$squareItems[$squareAll["square_max"]] = $squareAll["square_max"];
					}

					$squareMin = isset($this->squareCountMin) ? CHtml::encode($this->squareCountMin) : $squareAll['square_min'];
					$squareMax = isset($this->squareCountMax) ? CHtml::encode($this->squareCountMax) : max($squareItems);

					$selecttoslider = new SelectToSlider;
					$selecttoslider->publishAssets();

					echo '<div class="index-search-form square-search-select">';
						echo CHtml::dropDownList('squareMin', $squareMin, $squareItems, array('style' => 'display: none;'));
						echo CHtml::dropDownList('squareMax', $squareMax, $squareItems, array('style' => 'display: none;'));
						echo '<div class="vals">';
							echo '<div id="squareMin_selected_val" class="left">'.$squareMin.'</div>';
							echo '<div id="squareMax_selected_val" class="right">'.$squareMax.'</div>';
						echo '</div>';
					echo '</div>';
				echo '</span>';
				
				Yii::app()->clientScript->registerScript('square', '
					$("select#squareMin, select#squareMax").selectToUISlider({labels: 2, tooltip: false, tooltipSrc : "text",	labelSrc: "text"});
				', CClientScript::POS_READY);
			}
			else {
				?>
					<span class="search"><div class="<?php echo $textClass; ?>"><?php echo Yii::t('common', 'Apartment square to'); ?>:</div> </span>
					<input type="text" id="squareTo" name="square" class="<?php echo $fieldClass; ?>" value="<?php echo isset($this->squareCount) && $this->squareCount ? CHtml::encode($this->squareCount) : "";?>"/>&nbsp;
				<?php
				
				Yii::app()->clientScript->registerScript('squareTo', '				
					focusSubmit($("input#squareTo"));
				', CClientScript::POS_READY);
			}
		?>
</div>