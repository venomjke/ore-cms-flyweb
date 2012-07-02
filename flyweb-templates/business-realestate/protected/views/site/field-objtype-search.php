<div class="<?php echo $divClass; ?>">
	<div class="search"><?php echo Yii::t('common', 'Property type'); ?>:</div>
	<?php
	echo CHtml::dropDownList('objType', isset($this->objType) ? CHtml::encode($this->objType) : 0, CMap::mergeArray(array(0 => Yii::t('common', 'please select')), Apartment::getObjTypesArray()), array('class' => $fieldClass));
	Yii::app()->clientScript->registerScript('objType', '				
		focusSubmit($("select#objType"));
	', CClientScript::POS_READY);
	?>
</div>