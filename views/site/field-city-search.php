<?php
Yii::app()->clientScript->registerCoreScript( 'jquery.ui' );
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.multiselect.min.js');

Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/ui/jquery-ui.multiselect.css');
?>

<div class="<?php echo $divClass; ?>">
    <span class="search"><div class="<?php echo $textClass; ?>"><?php echo Yii::t('common', 'City') ?>:</div></span>

    <?php
    echo CHtml::dropDownList(
        'city[]',
        isset($this->selectedCity)?$this->selectedCity:'',
        $this->cityActive,
        array('class' => $fieldClass.' height17', 'multiple' => 'multiple')
    );

    Yii::app()->clientScript->registerScript('select-city', '
			$("#city")
				.multiselect({
					noneSelectedText: "выберите город",
					checkAllText: "выбрать все",
					uncheckAllText: "очистить",
					selectedText: "выбрано # из # доступных",
					minWidth: '.$minWidth.',
					classes: "search-input-new",
					multiple: "false",
					selectedList: 1
				}).multiselectfilter({
					label: "Быстрый поиск",
					placeholder: "введите первые буквы названия",
					width: 185
				});
		', CClientScript::POS_READY);
    ?>
</div>