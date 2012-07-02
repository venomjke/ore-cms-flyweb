<div class="<?php echo $divClass; ?>">
		<?php
			if (issetModule('selecttoslider') && param('useFloorSlider') == 1) {
				?>
				<span class="search"><div class="<?php echo $textClass; ?>"><?php echo Yii::t('common', 'Floor range'); ?>:</div> </span>
				<span class="search">
					<?php
					$floorItems = array_merge(
						//array(0 => 'любое'),
						range(0, param('moduleApartments_maxFloor', 30))
					);
					$floorMin = isset($this->floorCountMin) ? CHtml::encode($this->floorCountMin) : 0;
					$floorMax = isset($this->floorCountMax) ? CHtml::encode($this->floorCountMax) : max($floorItems);

					$selecttoslider = new SelectToSlider;
					$selecttoslider->publishAssets();

					echo '<div class="index-search-form floor-search-select">';
						echo CHtml::dropDownList('floorMin', $floorMin, $floorItems, array('style' => 'display: none;'));
						echo CHtml::dropDownList('floorMax', $floorMax, $floorItems, array('style' => 'display: none;'));
						echo '<div class="vals">';
							echo '<div id="floorMin_selected_val" class="left">'.$floorMin.'</div>';
							echo '<div id="floorMax_selected_val" class="right">'.$floorMax.'</div>';
						echo '</div>';
					echo '</div>';
				echo '</span>';
				
				Yii::app()->clientScript->registerScript('floor', '
					$("select#floorMin, select#floorMax").selectToUISlider({labels: 2, tooltip: false, tooltipSrc : "text",	labelSrc: "text"});
				', CClientScript::POS_READY);
			}
			else {
				?>
				<span class="search"><div class="<?php echo $textClass; ?>"><?php echo Yii::t('common', 'Flat on floor'); ?>:</div> </span>
				<?php
				$floorItems = array_merge(
					array(0 => 'любое'),
					range(1, param('moduleApartments_maxFloor', 30))
				);
				
				echo CHtml::dropDownList('floor', isset($this->floorCount)?CHtml::encode($this->floorCount):0, $floorItems, array('class' => $fieldClass));
				
				Yii::app()->clientScript->registerScript('floor', '				
					focusSubmit($("select#floor"));
				', CClientScript::POS_READY);
			}
		?>
</div>