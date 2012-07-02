<div class="<?php echo $divClass; ?>">
	<?php
	if (issetModule('selecttoslider') && param('usePriceSlider') == 1) {
		?>
		<span class="search"><div class="<?php echo $textClass; ?>" id="currency-title"><?php echo Yii::t('common', 'Price range'); ?>:</div> </span>
		<span class="search">
			<?php
			$apTypes = SearchForm::apTypes();

			if (is_array($apTypes) && count($apTypes['propertyType'] > 0)) {

				$propertyType = CJavaScript::encode($apTypes['propertyType']);
				Yii::app()->clientScript->registerScript('propertyType', "var propertyType = " . $propertyType . ";", CClientScript::POS_HEAD);

				foreach ($apTypes['propertyType'] as $key => $value) {
					$priceAll = Apartment::model()->getPriceMinMax($key);

					$priceAll['price_min'] = isset($priceAll['price_min']) ? $priceAll['price_min'] : 0;
					$priceAll['price_max'] = isset($priceAll['price_max']) ? $priceAll['price_max'] : 1000;

					$diffPrice = $priceAll['price_max'] - $priceAll['price_min'];

					if ($diffPrice <= 10)
						$step = 1;
					else
						$step = 10;
					
					if ($diffPrice > 100) {
						$step = 10;
					}
					if ($diffPrice > 1000) {
						$step = 100;
					}
					if ($diffPrice > 10000) {
						$step = 1000;
					}
					if ($diffPrice > 100000) {
						$step = 10000;
					}
					if ($diffPrice > 1000000) { // 1 million
						$step = 100000;
					}
					if ($diffPrice > 10000000) { // 10 millions
						$step = 1000000;
					}
					if ($diffPrice > 100000000) { // 100 millions
						$step = 10000000;
					}

					$priceItems = array_combine(
							range($priceAll['price_min'], $priceAll['price_max'], $step), range($priceAll['price_min'], $priceAll['price_max'], $step)
					);

					// add last element if step less
					if (max($priceItems) != $priceAll["price_max"]) {
						$priceItems[$priceAll["price_max"]] = $priceAll["price_max"];
					}

					$priceMin = (isset($this->priceSlider) && isset($this->priceSlider["min_{$key}"])) ? $this->priceSlider["min_{$key}"] : $priceAll["price_min"];
					$priceMax = (isset($this->priceSlider) && isset($this->priceSlider["max_{$key}"])) ? $this->priceSlider["max_{$key}"] : max($priceItems);

					$selecttoslider = new SelectToSlider;
					$selecttoslider->publishAssets();

					echo '<div style="display: none;" id="price-search-'.$key.'" class="index-search-form price-search-select">';
						echo CHtml::dropDownList('price_'.$key.'_Min', $priceMin, $priceItems, array('style' => 'display: none;'));
						echo CHtml::dropDownList('price_'.$key.'_Max', $priceMax, $priceItems, array('style' => 'display: none;'));
						echo '<div class="vals">';
							echo '<div id="price_'.$key.'_Min_selected_val" class="left">' . $priceMin . '</div>';
							echo '<div id="price_'.$key.'_Max_selected_val" class="right">' . $priceMax . '</div>';
						echo '</div>';
					echo '</div>';

					Yii::app()->clientScript->registerScript('price_'.$key.'', '
							$("select#price_'.$key.'_Min, select#price_'.$key.'_Max").selectToUISlider({labels: 2, tooltip: false, tooltipSrc : "text",	labelSrc: "text"});
						', CClientScript::POS_READY);

					
					echo '<div style="display: none;" id="price-currency-'.$key.'" class="slider-price-currency">руб/сутки</div>';
					
					unset($priceItems);
					unset($priceAll);
					unset($priceMin);
					unset($priceMax);
				}
			}
			echo '</span>';
		} else {
			?>
			<span class="search"><div class="<?php echo $textClass; ?>" id="currency-title"><?php echo Yii::t('common', 'Price to'); ?>:</div> </span>
			<span class="search">
				<input type="text" id="priceTo" name="price" class="<?php echo $fieldClass; ?>" value="<?php echo isset($this->price) && $this->price ? $this->price : ""; ?>"/>&nbsp;
				<span id="price-currency"><?php echo Yii::t('common', 'rub/day'); ?></span>
			</span>
	<?php
	
	Yii::app()->clientScript->registerScript('priceTo', '				
		focusSubmit($("input#priceTo"));
	', CClientScript::POS_READY);
}
?>
</div>