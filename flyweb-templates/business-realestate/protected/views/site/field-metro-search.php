<?php
	Yii::app()->clientScript->registerCoreScript( 'jquery.ui' );
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.multiselect.min.js');

	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/ui/jquery-ui.multiselect.css');
?>

<div class="<?php echo $divClass; ?>">
	<div class="<?php echo $textClass; ?>">Квартира рядом с:</div>

	<?php
		
		echo CHtml::dropDownList(
				'metro-select[]',
				isset($this->selectedStations)?$this->selectedStations:'',
				$this->metroStations,
				array('class' => $fieldClass.' height17', 'multiple' => 'multiple', 'style' => 'float:left')
			);
		

		Yii::app()->clientScript->registerScript('select-metro', '
			$("#metro-select")
				.multiselect({
					noneSelectedText: "выберите станции метро",
					checkAllText: "выбрать все",
					uncheckAllText: "очистить",
					selectedText: "выбрано # из # доступных",
					minWidth: '.$minWidth.',
					classes: "search-input-new",
					multiple: "false",
					selectedList: 1,
				}).multiselectfilter({
					label: "Быстрый поиск",
					placeholder: "введите первые буквы названия",
					width: 185
				});
		', CClientScript::POS_READY);
	?>
</div>