<?php

$this->adminTitle = Yii::t('module_payment','Payment System Settings');

if(count($systems) > 1){
	echo '<p>Выберите платежную систему для редактирования настроек: <br />';
	foreach($systems as $model){
		echo CHtml::link($model->translatedName, array('/payment/backend/paysystem/configure', 'id' => $model->id)).'<br />';
	}
	echo '</p>';
}



